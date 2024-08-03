<?php

namespace app\controllers;

use app\components\Middleware\TokenFilter;
use app\models\DTO\ProductsResponse;
use app\models\Products;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class ProductController extends Controller
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
                    'roles' => ['manager', 'user', 'snab', 'admin'],
                ],
            ],
        ];

        return $behaviors;
    }
    public function actionSearch(){
        $queryParams = Yii::$app->request->getQueryParams();
        $models = [];
        $response = [];
        if (isset($queryParams['favourites']) && $queryParams['favourites'] == 'true') {
            if (isset($queryParams['product_name']) && isset($queryParams['category_name'])) {
                $models = Products::find()
                    ->with('product-categories')
                    ->where(['like', 'name', $queryParams['product_name']])
                    ->andWhere(['like', 'category.name', $queryParams['category_name']])
                    ->all();
            }
        }elseif (isset($queryParams['category']) && $queryParams['category'] == 'true'){
            if (isset($queryParams['name'])) {
                $models = Products::find()
                    ->with('product-categories')
                    ->where(['like', 'category.name', $queryParams['name']])
                    ->all();
            }
        }elseif (isset($queryParams['product']) && $queryParams['product'] == 'true'){
            if (isset($queryParams['name'])) {
                $models = Products::find()
                    ->with('product-categories')
                    ->where(['like', 'name', $queryParams['name']])
                    ->all();
            }
        }else{
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'No checkboxes changed'];
        }
        foreach ($models as $model) {
            $modelResponse = new ProductsResponse();
            if($modelResponse->load($model)){
                $modelResponse->category = $model->category->name;
                $response[] = $modelResponse;
            }
        }
        return $response;
    }
}