<?php
namespace frontend\controllers;
class GameController extends \yii\web\Controller {

	public function actionIndex()
	{
	return $this->renderAjax('index');
	}

}