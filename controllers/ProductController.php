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
    public function actionSearch()
    {
        $queryParams = Yii::$app->request->getQueryParams();
        $product_name = $queryParams['product_name'] ?? '';
        $category_name = $queryParams['category_name'] ?? '';
        $searchName = $queryParams['checkbox_product'] ?? 0;
        $searchCategory = $queryParams['checkbox_category'] ?? 0;
        $searchFavorite = $queryParams['checkbox_favorite'] ?? 0;

        $query = (new \yii\db\Query())
            ->select(['p.id', 'p.name', 'p.length', 'p.width', 'p.height', 'p.weight', 'c.name AS category_name'])
            ->from('products p')
            ->leftJoin('product_category pc', 'pc.product_id = p.id')
            ->leftJoin('category c', 'c.id = pc.category_id');

        if ($searchFavorite){
            $query->andWhere(['like', 'p.name', $product_name]);
            $query->andWhere(['like', 'c.name', $category_name]);
        }
        elseif ($searchName) {
            $query->andWhere(['like', 'p.name', $product_name]);
        }
        elseif ($searchCategory) {
            $query->orWhere(['like', 'c.name', $category_name]);
        }
        $products = $query->all();
        return $products;
    }
}