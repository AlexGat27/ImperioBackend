<?php

namespace app\controllers;

use app\components\Middleware\TokenFilter;
use app\models\DTO\ManufactureForm;
use app\models\Manufactures;
use app\services\manufactures\GetManufacturesQueryService;
use app\services\manufactures\SearchManufacturesQueryService;
use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class ManufactureController extends Controller
{
    private SearchManufacturesQueryService $searchManufacturesQueryService;
    private GetManufacturesQueryService $getManufacturesQueryService;

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

    // Конструктор с двумя сервисами
    public function __construct($id, $module,
                                SearchManufacturesQueryService $searchManufacturesQueryService,
                                GetManufacturesQueryService $getManufacturesQueryService, $config = [])
    {
        $this->searchManufacturesQueryService = $searchManufacturesQueryService;
        $this->getManufacturesQueryService = $getManufacturesQueryService;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        return $this->getManufacturesQueryService->getManufacturesData();
    }

    public function actionView($id)
    {
        return $this->getManufacturesQueryService->getManufacturesById($id);
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
        return $this->searchManufacturesQueryService->searchManufacturesData($queryParams);
    }

    protected function findModel($id)
    {
        if (($model = Manufactures::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
