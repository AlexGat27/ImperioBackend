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
        $behaviors['tokenFilter'] = [
            'class' => TokenFilter::class,
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ],
        ];

        return $behaviors;
    }
    public function actionIndexParentid($parentid){
        if ($parentid){
            $cities = City::find()->where(['parentid' => $parentid])->all();
        }else{
            $cities = City::find()->all();
        }
        return $cities;
    }
}