<?php

namespace app\controllers;

use app\components\Middleware\TokenFilter;
use app\models\ManufactureContacts;
use app\models\Manufactures;
use yii\filters\AccessControl;
use yii\rest\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ManufactureContactController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['tokenFilter'] = [
            'class' => TokenFilter::class,
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['user', 'snab', 'admin'],
                ],
            ],
        ];
        return $behaviors;
    }
    public function actionIndex()
    {
        return ManufactureContacts::find()->all();
    }

    public function actionView($id)
    {
        return $this->findModel($id);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request->post();
        $manufactureName = $request['manufacture_name'] ?? null;

        if ($manufactureName) {
            $manufacture = Manufactures::findOne(['name' => $manufactureName]);
            if (!$manufacture) {
                throw new NotFoundHttpException('The requested manufacturer does not exist.');
            }

            $model = new ManufactureContacts();
            $model->load($request, '');
            $model->id_manufacture = $manufacture->id;

            if ($model->save()) {
                return $model;
            }
            return $model->errors;
        }

        throw new NotFoundHttpException('Manufacturer name is required.');
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request->post();
        if ($model) {
            $model->load($request, '');
            if ($model->save()) {
                return $model;
            }
            return $model->errors;
        }

        throw new NotFoundHttpException('Manufacturer name is required.');
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        return $model->delete();
    }

    protected function findModel($id)
    {
        if (($model = ManufactureContacts::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
