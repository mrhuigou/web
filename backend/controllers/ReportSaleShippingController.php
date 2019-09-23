<?php

namespace backend\controllers;

use api\models\V1\ReportSaleShippingSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;



class ReportSaleShippingController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex(){

        $searchModel = new ReportSaleShippingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}