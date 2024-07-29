<?php

namespace app\commands;

use app\models\User;
use Yii;
use yii\console\Controller;

class RootUserController extends Controller
{
    public function actionInit()
    {
        $user = new User();
        $user->login = "&&&&";
        $user->password = "&&&&";
        $user->name = "&&&&";
        $user->surname = "&&&&";
        $user->save();

        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole('admin'), $user->id);
    }
}