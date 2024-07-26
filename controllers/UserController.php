<?php

namespace app\controllers;


use app\components\Middleware\TokenFilter;
use app\components\TokenGenerator;
use app\components\TokenTools;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class UserController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['tokenFilter'] = [
            'class' => TokenFilter::class,
            'except' => ['login', 'refresh-tokens'],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'except' => ['login'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['users'],
                ],
                [
                    'actions' => ['profile', 'logout', 'refresh-tokens'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }
    public function actionIndex()  {
        $users = User::find()->orderBy('id')->all();
        $usersWithRoles = [];
        foreach($users as $user) {
            $auth = Yii::$app->authManager;
            $roles = $auth->getRolesByUser($user->id);
            $rolesKeys = array_keys($roles);
            $usersWithRoles[] = [
                ...$user,
                "roles" => $rolesKeys,
            ];
        }
        return $usersWithRoles;
    }

    public function actionRegister()
    {
        $user = new User();
        $request = Yii::$app->request;

        // Загружаем данные из POST-запроса в модель пользователя
        $user->load($request->post(), '');
        $user->login = $request->post('login');

        // Сохраняем пользователя в базе данных
        if ($user->save()) {
            // Назначаем роль пользователю
            $auth = Yii::$app->authManager;

            // По умолчанию назначаем роль 'user'
            $role = $auth->getRole('user');

            // Если указана роль пользователя, найдем ее
            if ($request->post('role_name')) {
                $requestedRole = $auth->getRole($request->post('role_name'));
                if ($requestedRole !== null) {
                    $role = $requestedRole;
                }
            }
            $auth->assign($role, $user->id);
            return ['message' => 'Registration successful', 'user' => $user];
        } else {
            return ['message' => 'Registration failed', 'errors' => $user->errors];
        }
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $login = $request->post('login');
        $password = $request->post('password');
        $ipAddress = $request->userIP;
        $userAgent = $request->userAgent;

        // Находим пользователя по имени пользователя
        $user = User::findOne(['login' => $login]);

        // Проверяем правильность пароля
        if ($user && $user->validatePassword($password)) {
            $tokenGenerator = new TokenGenerator($user, $ipAddress, $userAgent);
            $newToken = $tokenGenerator->generateTokens();

            if ($newToken) {
                Yii::$app->user->login($user);
                Yii::$app->response->headers->set('Authorization', 'Bearer ' . $newToken);
                return ['access_token' => $newToken];
            } else {
                return ['message' => 'Failed to generate refresh token'];
            }
        } else {
            return ['message' => 'Invalid username or password'];
        }
    }
    public function actionLogout()
    {
        $authorizationHeader = Yii::$app->request->headers->get('Authorization');
        if ($authorizationHeader && preg_match('/^Bearer\s+(.*?)$/', $authorizationHeader, $matches)) {
            $token = $matches[1]; // В этой переменной будет содержаться токен
        } else {
            $token = null;
        }
        if ($token !== null && !Yii::$app->user->isGuest) {
            $user_id = Yii::$app->user->identity->id;
            $request = Yii::$app->request;
            $ipAddress = $request->userIP;
            $userAgent = $request->userAgent;
            TokenTools::clearRefreshToken($user_id, $userAgent, $ipAddress);
            Yii::$app->user->logout();
        } else {
            Yii::$app->response->statusCode = 401; // Unauthorized
            return ['error' => 'Token not found'];
        }

        return ['message' => 'Logout successful']; // Возвращаем сообщение об успешном выходе
    }

    public function actionProfile()
    {
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches);
        $token = $matches[1];
        $userId = TokenTools::getUserId($token);
        $user = User::findIdentity($userId);
        if (!$user) {
            throw new NotFoundHttpException("User not found");
        }
        return $user;
    }

    public function actionUpdate($id)
    {
        $user = User::findIdentity($id);
        if (!$user) {
            throw new NotFoundHttpException("User not found");
        }

        $request = Yii::$app->request;
        $user->load($request->getBodyParams(), '');

        if ($user->save()) {
            if (null !== $request->post("roles")){
                $auth = Yii::$app->authManager;
                $auth->revokeAll($user->id);
                foreach ($request->post("roles") as $role_name) {
                    $role = $auth->getRole($role_name);
                    $auth->assign($role, $user->id);
                }
            }
            return ['message' => 'User updated successfully', 'user' => $user];
        } else {
            return ['message' => 'Failed to update user', 'errors' => $user->errors];
        }

    }

    public function actionDelete($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("User not found");
        }

        if ($user->delete()) {
            TokenTools::clearRefreshToken();
            $auth = Yii::$app->authManager;
            $auth->revokeAll($user->id);
            return ['message' => 'User deleted successfully'];
        } else {
            return ['message' => 'Failed to delete user'];
        }
    }

    public function actionRefreshTokens()
    {
        $token = Yii::$app->request->cookies->get('refreshToken');

        if ($token) {
            $userId = TokenTools::getUserId($token);
            $ipAddress = Yii::$app->request->userIP;
            $userAgent = Yii::$app->request->userAgent;
            $user = User::findOne($userId);
            if ($user) {
                $tokenGenerator = new TokenGenerator($user, $ipAddress, $userAgent);
                return ['access_token' => $tokenGenerator->refreshTokens()];
            }
        } else {
            Yii::$app->response->statusCode = 401;
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = [
                'status' => 'error',
                'message' => 'No token provided or invalid authorization header',
            ];
            return false;
        }
    }

    public function actionGetRole($id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($id);

        if (!empty($roles)) {
            return $roles;
        } else {
            return ['message' => 'No roles found for this user'];
        }
    }
}
