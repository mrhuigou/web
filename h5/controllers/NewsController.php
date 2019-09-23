<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/12
 * Time: 19:09
 */

namespace h5\controllers;
use api\models\V1\Information;
use api\models\V1\News;
use api\models\V1\NewsLog;
use api\models\V1\WeixinMessage;
use yii\data\ActiveDataProvider;
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
    public function actionList(){
	    if(!$url=\Yii::$app->request->get('redirect')){
		    $url = "/news/list";
	    }
	    if (\Yii::$app->user->isGuest) {
		    return $this->redirect(['/site/login','redirect'=>$url]);
	    }
        $model=News::find()->where(['channel'=>1,'status'=>1])->orderBy("sort_order asc");

	    if($data=$model->all()){
	    	foreach($data as $value){
				if(!$log=NewsLog::findOne(['new_id'=>$value->news_id,'customer_id'=>\Yii::$app->user->getId()])){
					$log=new NewsLog();
					$log->customer_id=\Yii::$app->user->getId();
					$log->new_id=$value->news_id;
					$log->creat_at=time();
					$log->save();
				}
		    }
	    }
        return $this->redirect('/read-more/index');
    }
}