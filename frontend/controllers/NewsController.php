<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/12
 * Time: 19:09
 */

namespace frontend\controllers;
use api\models\V1\Information;
use api\models\V1\News;
use api\models\V1\WeixinMessage;
use yii\web\NotFoundHttpException;

class NewsController extends \yii\web\Controller
{
	public function actionIndex(){
		if(!$model=News::findOne(['news_id'=>\Yii::$app->request->get('news_id')])){
			throw new NotFoundHttpException("没有找到当前内容");
		}
		return $this->render('news',['model'=>$model]);
	}
	public function actionInfo(){
		if(!$model=Information::findOne(['information_id'=>\Yii::$app->request->get('news_id')])){
			throw new NotFoundHttpException("没有找到当前内容");
		}
		return $this->render('info',['model'=>$model]);
	}
	public function actionDetail(){
		if(!$model=WeixinMessage::findOne(['id'=>\Yii::$app->request->get('id')])){
			throw new NotFoundHttpException("没有找到当前内容");
		}
		return $this->render('detail',['model'=>$model]);
	}
}