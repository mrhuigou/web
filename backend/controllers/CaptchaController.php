<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/7/10
 * Time: 10:31
 */
namespace backend\controllers;
use backend\models\CaptchaSearch;
use Yii;
use yii\web\Controller;
class CaptchaController extends Controller{
	public function actionIndex()
	{
		$searchModel = new CaptchaSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

}