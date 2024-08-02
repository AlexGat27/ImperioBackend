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

        $admin = $auth->createRole('admin');
        $admin->description = 'The main admin with all privilegies';
        $auth->add($admin);

        $snab = $auth->createRole('snab');
        $snab->description = 'The supplier';
        $auth->add($snab);

        $manager = $auth->createRole('manager');
        $manager->description = 'This is the manager';
        $auth->add($manager);

        $logist = $auth->createRole('logist');
        $logist->description = 'This is the logist';
        $auth->add($logist);

        $user = $auth->createRole('user');
        $user->description = 'This is the user';
        $auth->add($user);
    }
}
