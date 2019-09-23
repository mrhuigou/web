<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/28
 * Time: 15:56
 */
namespace h5\controllers;
use api\models\V1\CustomerOther;
use h5\models\EnrollForm;

class EnrollController extends \yii\web\Controller {
	public function actionIndex(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login','redirect'=>'/enroll/index']);
		}
		$model = new EnrollForm();
		if ($model->load(\Yii::$app->request->post()) && $data=$model->save()) {
			return $this->redirect(['view', 'id' => $data->id]);
		} else {
			return $this->render('index', [
				'model' => $model,
			]);
		}
	}
	public function actionView($id){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login','redirect'=>'/enroll/index']);
		}
		$model=CustomerOther::findOne($id);
		return $this->render('view',['model'=>$model]);
	}
}