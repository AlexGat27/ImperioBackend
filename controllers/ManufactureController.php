<?php

namespace app\controllers;

use app\components\Middleware\TokenFilter;
use app\models\Catalog;
use app\models\DTO\ManufactureForm;
use app\models\DTO\ManufactureResponse;
use app\models\Manufactures;
use Yii;
use yii\db\Query;
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

    // Extracted method to retrieve common manufacture data
    protected function getManufactureData($queryParams = [])
    {
        $query = (new \yii\db\Query())
            ->select(['m.id', 'm.name', 'm.website', 'm.address_loading', 'm.note', 'm.create_your_project',
                'm.is_work', 'c.name AS category_name', 'r.name AS region_name', 'ci.name AS city_name',
                'd.name AS district_name'])
            ->from('manufactures m')
            ->leftJoin('manufacture_category mc', 'm.id = mc.manufacture_id')
            ->leftJoin('category c', 'mc.category_id = c.id')
            ->leftJoin('city r', 'm.id_region = r.id')
            ->leftJoin('city ci', 'm.id_city = ci.id')
            ->leftJoin('city d', 'r.parentid = d.id');

        // Add filtering if query params are provided
        if (!empty($queryParams)) {
            if (isset($queryParams['category'])) {
                $query->andWhere(['like', 'c.name', $queryParams['category']]);
                if (isset($queryParams['district'])) {
                    $query->andWhere(['like', 'd.name', $queryParams['district']]);
                    if (isset($queryParams['region'])) {
                        $query->andWhere(['like', 'r.name', $queryParams['region']]);
                        if (isset($queryParams['city'])) {
                            $query->andWhere(['like', 'ci.name', $queryParams['city']]);
                        }
                    }
                }
            }
        }

        $manufactures = $query->all();
        $response = [];
        foreach ($manufactures as $manufacture) {
            $emails = (new \yii\db\Query())
                ->select('email')
                ->from('manufacture_emails')
                ->where(['id_manufacture' => $manufacture['id']])
                ->column();

            $contacts = (new \yii\db\Query())
                ->select(['telephone', 'name_personal', 'note'])
                ->from('manufacture_contacts')
                ->where(['id_manufacture' => $manufacture['id']])
                ->all();

            $response[] = [
                "id" => $manufacture['id'],
                "name" => $manufacture['name'],
                "website" => $manufacture['website'],
                "category" => $manufacture['category_name'],
                "region" => $manufacture['region_name'],
                "city" => $manufacture['city_name'],
                "district" => $manufacture['district_name'],
                "emails" => $emails,
                "contacts" => $contacts,
            ];
        }

        return $response;
    }

    public function actionIndex()
    {
        return $this->getManufactureData();
    }

    public function actionView($id)
    {
        $model = Manufactures::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $emails = (new \yii\db\Query())
            ->select('email')
            ->from('manufacture_emails')
            ->where(['id_manufacture' => $model->id])
            ->column();

        $contacts = (new \yii\db\Query())
            ->select(['telephone', 'name_personal', 'note'])
            ->from('manufacture_contacts')
            ->where(['id_manufacture' => $model->id])
            ->all();

        return [
            "model" => $model,
            "emails" => $emails,
            'contacts' => $contacts,
        ];
    }

    public function actionCreate()
    {
        $form = new ManufactureForm();
        $form->load(Yii::$app->request->post(), '');
        if ($form->save()) {
            return $form;
        }
        return ['errors' => $form->errors];
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $form = new ManufactureForm();
        $form->loadFromModel($model);
        $form->load(Yii::$app->request->post(), '');
        if ($form->save()) {
            return $form;
        }
        return $form->errors;
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_work = 0;
        if ($model->save()) {
            return ['success' => true, 'deleted_model' => $model];
        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }

    public function actionSearchInManufactures()
    {
        $queryParams = Yii::$app->request->getQueryParams();
        return $this->getManufactureData($queryParams);
    }

    protected function findModel($id)
    {
        if (($model = Manufactures::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
