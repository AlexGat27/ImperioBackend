<?php

namespace app\controllers;

use app\components\Middleware\TokenFilter;
use app\models\City;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class CityController extends Controller
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
                    'roles' => ['city'],
                ],
            ],
        ];

        return $behaviors;
    }
    public function actionIndexParentid($parentid){
        $cities = City::find()->where(['parentid' => $parentid])->all();
        return $cities;
    }
}