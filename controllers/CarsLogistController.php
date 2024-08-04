<?php

namespace app\controllers;

use app\components\Middleware\TokenFilter;
use app\models\CarsLogist;
use app\models\City;
use app\models\DTO\CarsLogistResponse;
use app\models\TypeCars;
use Yii;
use yii\db\Query;
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
        $model->load($postData, '');
        if ($model->save()) {
            return ['status' => 'success', 'model' => $model];
        }
        Yii::$app->response->statusCode = 400;
        return ['status' => 'error', 'errors' => $model->getErrors()];
    }
    public function actionSearch(){
        $queryParams = Yii::$app->request->getQueryParams();
        $query = (new Query())
            ->select([
                'cars_logist.name',
                'cars_logist.telephone',
                'cars_logist.email',
                'cars_logist.notes',
                'GROUP_CONCAT(DISTINCT city.name ORDER BY city.name ASC) AS region_names',
                'GROUP_CONCAT(DISTINCT type_cars.name ORDER BY type_cars.name ASC) AS car_type_names'])
            ->from('cars_logist')
            ->leftJoin('cars_logist_type_cars', 'cars_logist_type_cars.cars_logist_id = cars_logist.id')
            ->leftJoin('type_cars', 'type_cars.id = cars_logist_type_cars.type_cars_id')
            ->leftJoin('city', 'city.parentid = cars_logist.fedDist_id')
            ->groupBy('cars_logist.name, cars_logist.telephone, cars_logist.email, cars_logist.notes');

        if (isset($queryParams['type_cars_id'])) {
            $query->andWhere(['type_cars.id' => $queryParams['type_cars_id']]);
            if (isset($queryParams['district_id'])) {
                $query->andWhere(['city.parentid' => $queryParams['district_id']]);
                if (isset($queryParams['region'])) {
                    $query->andWhere(['like', 'city.name', $queryParams['region']]);
                }
            }
        }
        $cars_logist = $query->all();
        foreach ($cars_logist as &$logist) {
            $logist['region_names'] = explode(',', $logist['region_names']);
            $logist['car_type_names'] = $logist['car_type_names'] ? explode(',', $logist['car_type_names']) : [];
        }
        return $cars_logist;
    }
}