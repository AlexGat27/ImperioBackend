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

    public function actionSearchInManufactures()
    {
        $queryParams = Yii::$app->request->getQueryParams();

        // Создаем базовый запрос
        $query = (new \yii\db\Query())
            ->select(['m.id', 'm.name AS manufacture_name', 'm.website', 'c.name AS category_name',
                'r.name AS region_name', 'ci.name AS city_name', 'd.name AS district_name'])
            ->from('manufactures m')
            ->leftJoin('manufacture_category mc', 'm.id = mc.manufacture_id')
            ->leftJoin('category c', 'mc.category_id = c.id')
            ->leftJoin('city r', 'm.id_region = r.id')
            ->leftJoin('city ci', 'm.id_city = ci.id')
            ->leftJoin('city d', 'r.parentid = d.id');

        // Добавляем фильтрацию по параметрам
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
        $manufactures = $query->all();
        $response = [];
        foreach ($manufactures as $manufacture) {
            // Получаем электронные адреса
            $emailsQuery = (new \yii\db\Query())
                ->select('email')
                ->from('manufacture_emails')
                ->where(['id_manufacture' => $manufacture['id']]);
            $emails = $emailsQuery->column();

            // Получаем контакты
            $contactsQuery = (new \yii\db\Query())
                ->select(['telephone', 'name_personal', 'note'])
                ->from('manufacture_contacts')
                ->where(['id_manufacture' => $manufacture['id']]);
            $contacts = $contactsQuery->all();

            // Формируем ответ
            $response[] = [
                "manufacture_name" => $manufacture['manufacture_name'],
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


    protected function findModel($id)
    {
        if (($model = Manufactures::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
