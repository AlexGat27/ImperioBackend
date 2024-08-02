<?php

namespace app\controllers;

use app\components\Middleware\TokenFilter;
use app\models\DTO\ManufactureResponse;
use app\models\ManufactureForm;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use app\models\Manufacture;
use app\models\ManufactureEmail;
use app\models\ManufactureContact;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ManufactureController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
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

    public function actionSearchinmanufacture()
    {
        $queryParams = Yii::$app->request->getQueryParams();
        $query = Manufacture::find();

        if (isset($queryParams['category'])) {
            $query->andWhere(['category' => $queryParams['category']]);
        }
        if (isset($queryParams['district'])) {
            $query->andWhere(['district' => $queryParams['district']]);
        }
        if (isset($queryParams['region'])) {
            $query->andWhere(['region' => $queryParams['region']]);
        }
        if (isset($queryParams['city'])) {
            $query->andWhere(['city' => $queryParams['city']]);
        }
        $manufactures = $query->with(['manufactureEmails', 'manufactureContacts'])->all();
        $response = [];
        foreach ($manufactures as $manufacture) {
            $manufactureResponse = new ManufactureResponse();
            if($manufactureResponse->load($manufacture->toArray())){
                $response[] = $manufactureResponse;
            }
            else{
                Yii::$app->response->statusCode = 400;
                return ['status' => 'error', 'message' => $manufactureResponse->errors];
            }
        }
        return $response;
    }

    protected function findModel($id)
    {
        if (($model = Manufacture::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
