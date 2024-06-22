<?php

namespace app\controllers;


use app\components\Middleware\TokenFilter;
use app\components\TokenGenerator;
use app\components\TokenTools;
use app\models\Role;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends Controller
{
    public $enableCsrfValidation = false;

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
            'only' => ['profile', 'logout', 'update', 'delete'],
        ];

        return $behaviors;
    }
    public function actionRegister()
    {
        $user = new User();
        $request = Yii::$app->request;

        // Если указана роль пользователя, найдем ее ID
        if ($request->post('role_name')) {
            $role_id = Role::findOne(['name' => $request->post('role_name')])->id;
            $user->role_id = $role_id;
        }

        // Загружаем данные из POST-запроса в модель пользователя
        $user->load($request->post(), '');

        // Сохраняем пользователя в базе данных
        if ($user->save()) {
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

        if ($token !== null && Yii::$app->user->isGuest) {
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
        if ($user) {
            $user->load(Yii::$app->request->getBodyParams(), ''); // Загружаем данные из PUT-запроса в модель пользователя
            if ($user->save()) {
                return $user; // Возвращаем обновленные данные пользователя
            } else {
                return ['error' => $user->errors]; // Возвращаем ошибку сохранения, если есть ошибки валидации
            }
        } else {
            throw new NotFoundHttpException("User not found with id: $id"); // Возвращаем ошибку, если пользователь не найден
        }
    }

    /**
     * Действие для удаления пользователя.
     * Пример запроса: DELETE /user/delete/{id}
     * Удаляет пользователя по его ID и возвращает сообщение об успешном удалении.
     */
    public function actionDelete($id)
    {
        // Находим пользователя по его ID
        $user = User::findOne($id);

        // Проверяем, найден ли пользователь
        if ($user) {
            $user->delete(); // Удаляем пользователя
            return ['message' => 'User deleted successfully']; // Возвращаем сообщение об успешном удалении
        } else {
            throw new NotFoundHttpException("User not found with id: $id"); // Возвращаем ошибку, если пользователь не найден
        }
    }
}
