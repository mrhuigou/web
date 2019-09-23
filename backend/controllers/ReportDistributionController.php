<?php

namespace backend\controllers;

use backend\models\ReportDistributionSearch;
use Yii;
use yii\web\Controller;



class ReportDistributionController extends Controller
{

	public function actionIndex(){

		$searchModel = new ReportDistributionSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
}