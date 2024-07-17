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
        $user->login = "root";
        $user->password = "shurikgat2704";
        $user->name = "root";
        $user->surname = "root";
        $user->save();

        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole('admin'), $user->id);
    }
}