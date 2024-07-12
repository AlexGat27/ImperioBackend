<?php

namespace app\controllers;


use app\components\Middleware\TokenFilter;
use app\components\TokenGenerator;
use app\components\TokenTools;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
            'except' => ['login'],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'except' => ['login'],
            'rules' => [
                [
                    'actions' => ['profile', 'logout'],
                    'allow' => true,
                    'roles' => ['user'],
                ],
                [
                    'allow' => true,
                    'roles' => ['admin'], // Только для пользователей с ролью 'admin'
                ],
            ],
        ];

        return $behaviors;
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
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("User not found");
        }

        $request = Yii::$app->request;
        $user->load($request->getBodyParams(), '');

        if ($user->save()) {
            if (isset($request["role_name"])){
                $auth = Yii::$app->authManager;
                $role = $auth->getRole($request["role_name"]);
                Yii::$app->authManager->assign($role, $user->id);
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
            TokenTools::clearRefreshTokens($id);
            return ['message' => 'User deleted successfully'];
        } else {
            return ['message' => 'Failed to delete user'];
        }
    }
}
