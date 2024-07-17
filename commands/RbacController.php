<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Удаление всех предыдущих данных
        $auth->removeAll();

        // Создание разрешений
        $usersPerm = $auth->createPermission('users');
        $usersPerm->description = 'Interaction with users';
        $auth->add($usersPerm);

        $manufacturesPerm = $auth->createPermission('manufactures');
        $manufacturesPerm->description = 'Interaction with manufactures';
        $auth->add($manufacturesPerm);

        $rolesPerm = $auth->createPermission('roles');
        $rolesPerm->description = 'Interaction with roles';
        $auth->add($rolesPerm);

        // Создание ролей
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $usersPerm);
        $auth->addChild($admin, $manufacturesPerm);
        $auth->addChild($admin, $rolesPerm);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $manufacturesPerm);
    }
}
