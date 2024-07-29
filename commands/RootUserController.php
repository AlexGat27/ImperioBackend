<?php

namespace app\commands;

use app\models\User;
use Yii;
use yii\console\Controller;

class RootUserController extends Controller
{
    public function actionInit()
    {
        User::findOne(1)->delete();
        $user = new User();
        $user->id = 1;
        $user->login = "&&&&";
        $user->password = "&&&&";
        $user->name = "&&&&";
        $user->surname = "&&&&";
        $user->save();

        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole('admin'), $user->id);
    }
}