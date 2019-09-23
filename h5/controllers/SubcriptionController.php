<?php
namespace h5\controllers;

use Yii;
use yii\web\Controller;


/**
 * Site controller
 */
class SubcriptionController extends Controller
{
	public function actionIndex(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
		}
		if($redirect=\Yii::$app->session->get('subcription_url')){
			return $this->redirect($redirect);
		}else{
			return $this->redirect('/site/index');
		}
	}
}