<?php

namespace app\controllers;

use app\models\Manufacture;
use yii\rest\Controller;
use app\models\ManufactureContact;
use Yii;
use yii\web\NotFoundHttpException;

class ManufactureContactController extends Controller
{
    public function actionIndex()
    {
        return ManufactureContact::find()->all();
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
            $manufacture = Manufacture::findOne(['name' => $manufactureName]);
            if (!$manufacture) {
                throw new NotFoundHttpException('The requested manufacturer does not exist.');
            }

            $model = new ManufactureContact();
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
        if (($model = ManufactureContact::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
