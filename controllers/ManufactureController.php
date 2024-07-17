<?php

namespace app\controllers;

use app\models\ManufactureForm;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use app\models\Manufacture;
use app\models\ManufactureEmail;
use app\models\ManufactureContact;
use Yii;
use yii\web\NotFoundHttpException;

class ManufactureController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manufactures'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $manufactures = Manufacture::find()->all();
        $response = [];

        foreach ($manufactures as $manufacture) {
            $emails = $manufacture->getManufactureEmails()->asArray()->all();
            $contacts = $manufacture->getManufactureContacts()->asArray()->all();

            $response[] = [
                "model" => $manufacture->attributes,
                "emails" => $emails,
                "contacts" => $contacts,
            ];
        }

        return $response;
    }

    public function actionView($id)
    {
        $model = Manufacture::findOne($id);
        return [
            "model" => $model,
            "emails" => $model->manufactureEmails,
            'contacts' => $model->manufactureContacts,
        ];
    }

    public function actionCreate()
    {
        $form = new ManufactureForm();
        $form->load(Yii::$app->request->post(), '');
        $savedModel = $form->save();
        if ($savedModel) {
            return $savedModel;
        }
        return $form->errors;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $form = new ManufactureForm();
        $form->loadFromModel($model);
        $form->load(Yii::$app->request->post(), '');
        $savedModel = $form->save();
        if ($savedModel) {
            return $savedModel;
        }
        return $form->errors;
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_work = false;
        return $model->save();
    }

    protected function findModel($id)
    {
        if (($model = Manufacture::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
