<?php

namespace app\controllers;

use app\components\Middleware\TokenFilter;
use app\models\Catalog;
use app\models\DTO\ManufactureForm;
use app\models\DTO\ManufactureResponse;
use app\models\Manufactures;
use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ManufactureController extends Controller
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
        $manufactures = Manufactures::find()->all();
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
        $model = Manufactures::findOne($id);
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

    public function actionSearchInManufacture()
    {
        $queryParams = Yii::$app->request->getQueryParams();
        $query = Manufactures::find();

        if (isset($queryParams['category'])) {
            $query->joinWith(['catalogs' => function($q) use ($queryParams) {
                $q->andWhere(['catalog.name' => $queryParams['category']]);
            }]);
            if (isset($queryParams['district'])) {
                $query->joinWith(['district' => function($q) use ($queryParams) {
                    $q->andWhere(['city.name' => $queryParams['district']]);
                }]);
                if (isset($queryParams['region'])) {
                    $query->joinWith(['region' => function($q) use ($queryParams) {
                        $q->andWhere(['city.name' => $queryParams['region']]);
                    }]);
                    if (isset($queryParams['city'])) {
                        $query->joinWith(['city' => function($q) use ($queryParams) {
                            $q->andWhere(['city.name' => $queryParams['city']]);
                        }]);
                    }
                }
            }
        }
        $manufactures = $query->with(['manufactureEmails', 'manufactureContacts'])->all();
        $response = [];
        foreach ($manufactures as $manufacture) {
            $manufactureResponse = new ManufactureResponse();
            if($manufactureResponse->load($manufacture->toArray())){
                $manufactureResponse->region = $manufacture->region->name ?? '';
                $manufactureResponse->city = $manufacture->city->name ?? '';
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
        if (($model = Manufactures::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
