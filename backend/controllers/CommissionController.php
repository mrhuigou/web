<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/10
 * Time: 19:19
 */
namespace backend\controllers;
use api\models\V1\CustomerAffiliate;
use api\models\V1\CustomerAffiliateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CommissionController extends Controller{
	public function actionIndex()
	{
		$searchModel = new CustomerAffiliateSearch();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	public function actionCreate()
	{
		$model = new CustomerAffiliate();
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			$model->date_added = date('Y-m-d H:i:s',time());
			$model->save();
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}
	protected function findModel($id)
	{
		if (($model = CustomerAffiliate::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

}