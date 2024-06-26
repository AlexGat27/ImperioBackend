<?php

namespace app\controllers;

use app\models\AuthRole;
use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class RoleController extends ActiveController
{
    public $modelClass = AuthRole::class;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'], // Только для пользователей с ролью 'admin'
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $roles = AuthRole::find()->all();
        return $roles;
    }

    public function actionView($name)
    {
        if (($role = AuthRole::findOne($name)) !== null) {
            return $role;
        }

        throw new NotFoundHttpException('Not found role');
    }

    public function actionCreate()
    {
        $role = new AuthRole();

        if ($role->load(Yii::$app->request->post()) && $role->save()) {
            return $role;
        }

        return ["message" => "Cannot create role."];
    }

    public function actionUpdate($name)
    {
        $role = $this->findModel($name);

        if ($role->load(Yii::$app->request->post()) && $role->save()) {
            return $role;
        }

        return ["message" => "Cannot update role."];
    }

    public function actionDelete($name)
    {
        $this->findModel($name)->delete();
        return ["message" => "Successfully deleted role"];
    }
}