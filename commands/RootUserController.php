<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

class RootUserController extends Controller
{
    public function actionInit()
    {
        $userId = 1;

        // Поиск существующего пользователя с ID 1
        $user = User::findOne($userId);
        $auth = Yii::$app->authManager;

        // Если пользователь найден, удаляем его
        if ($user !== null) {
            $user->delete();
            $auth->revokeAll($userId);
        }

        // Создание нового пользователя
        $user = new User();
        $user->id = $userId;
        $user->login = "&&&&";
        $user->name = "&&&&";
        $user->surname = "&&&&";

        // Хеширование пароля перед сохранением
        $user->password = Yii::$app->security->generatePasswordHash("&&&&");

        try {
            if ($user->save()) {
                // Получение компонента управления доступом


                // Проверка существования роли 'admin'
                $role = $auth->getRole('admin');
                if ($role !== null) {
                    $auth->assign($role, $user->id);
                } else {
                    throw new \Exception('Role "admin" does not exist.');
                }

                echo "User created and role assigned successfully.";
            } else {
                throw new \Exception('Failed to save the user.');
            }
        } catch (\Exception $e) {
            // Обработка ошибок
            echo "Error: " . $e->getMessage();
        }
    }
}
