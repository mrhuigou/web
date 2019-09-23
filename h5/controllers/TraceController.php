<?php
namespace h5\controllers;

use api\models\V1\WebLogVisit;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class TraceController extends Controller
{
	public function actionIndex(){
		if(Yii::$app->request->isAjax){
			if($data=Yii::$app->request->post('trace_data')){
				$model=new WebLogVisit();
				$model->user_identity=md5($data['user_identity']?$data['user_identity']:'');
				if(!Yii::$app->user->isGuest){
					$model->customer_id=Yii::$app->user->getId();
				}
				$model->title=Html::encode($data['title']);
				$model->url=$data['url'];
				$model->refer=$data['refer'];
				$model->pf=$data['pf'];
				$model->ua=$data['ua'];
				$model->language=$data['language'];
				$model->screen=$data['screen'];
				$model->color_depth=$data['color_depth'];
				$model->time=$data['time'];
				$model->time_in=$data['time_in'];
				$model->time_out=$data['time_out'];
				$model->source_ip=Yii::$app->request->getUserIP();
				$model->save();
			}
		}

	}
}