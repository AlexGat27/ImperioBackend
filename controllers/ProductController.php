<?php

namespace app\controllers;

use app\components\Middleware\TokenFilter;
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
        $search_query = $queryParams['search_query'] ?? '';
        $searchName = $queryParams['checkbox_product'] ?? 0;
        $searchCategory = $queryParams['checkbox_category'] ?? 0;

        $query = (new \yii\db\Query())
            ->select(['p.id', 'p.name', 'p.length', 'p.width', 'p.height', 'p.weight', 'c.name AS category_name'])
            ->from('products p')
            ->leftJoin('product_category pc', 'pc.product_id = p.id')
            ->leftJoin('category c', 'c.id = pc.category_id');

        if ($searchName == 'true' && $searchCategory == 'true') {
            $query->andWhere(['like', 'p.name', $search_query]);
            $query->andWhere(['like', 'c.name', $search_query]);
        }
        elseif ($searchName == 'true') {
            $query->andWhere(['like', 'p.name', $search_query]);
        }
        elseif ($searchCategory == 'true') {
            $query->orWhere(['like', 'c.name', $search_query]);
        }
        $products = $query->all();
        return $products;
    }
}