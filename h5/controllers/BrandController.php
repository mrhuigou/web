<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/29
 * Time: 10:13
 */
namespace h5\controllers;
class BrandController extends \yii\web\Controller {
	public function actionIndex()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}

		return $this->render('index',['model'=>'']);
	}

}