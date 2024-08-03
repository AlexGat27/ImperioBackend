<?php

namespace app\controllers;

use app\components\Middleware\TokenFilter;
use app\models\CarsLogist;
use app\models\DTO\CarsLogistResponse;
use app\models\TypeCars;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class CarsLogistController extends Controller
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
                    'roles' => ['logist', 'admin'],
                ],
            ],
        ];

        return $behaviors;
    }
    public function actionIndexTypeCars()
    {
        return TypeCars::find()->all();
    }
    public function actionCreate(){
        $postData = Yii::$app->request->getBodyParams();
        $model = new CarsLogist();
        $model->load($postData);
        if ($model->save()) {
            return ['status' => 'success', 'model' => $model];
        }
        Yii::$app->response->statusCode = 400;
        return ['status' => 'error', 'errors' => $model->getErrors()];
    }
    public function actionSearch(){
        $queryParams = Yii::$app->request->getQueryParams();
        $query = CarsLogist::find();

        if (isset($queryParams['type_car_id'])) {
            $query->joinWith(['type_cars' => function($q) use ($queryParams) {
                $q->andWhere(['type_cars.id' => $queryParams['type_car_id']]);
            }]);
            if (isset($queryParams['district_id'])) {
                $query->joinWith(['district' => function($q) use ($queryParams) {
                    $q->andWhere(['city.id' => $queryParams['district_id']]);
                }]);
                if (isset($queryParams['region'])) {
                    $query->joinWith(['region' => function($q) use ($queryParams) {
                        $q->andWhere(['city.name' => $queryParams['region']]);
                    }]);
                }
            }
        }
        $logists = $query->all();
        $response = [];
        foreach ($logists as $logist) {
            $logistResponse = new CarsLogistResponse();
            if($logistResponse->load($logist->toArray())){
                $logistResponse->type_cars_name = $logist->type_cars->name;

                $response[] = $logistResponse;
            }
            else{
                Yii::$app->response->statusCode = 400;
                return ['status' => 'error', 'message' => $logistResponse->errors];
            }
        }
        return $response;
    }
}