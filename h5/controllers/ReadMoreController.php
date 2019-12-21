<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/3/29
 * Time: 9:55
 */
namespace h5\controllers;
use api\models\V1\News;
use api\models\V1\NewsCategory;
use yii\data\ActiveDataProvider;

class ReadMoreController extends \yii\web\Controller{
	public function actionIndex(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
		}
        $new_category_id = \Yii::$app->request->get("c");
		if(!$new_category_id){
            $new_category_id = 1; //默认每日惠购快报
        }
        $new_category = NewsCategory::findOne(['id'=>$new_category_id]);

		$page=\Yii::$app->request->get('page',0);
		$model=News::find()->where(['channel'=>1,'news_category_id'=>$new_category_id,'status'=>1]);
		$page_count=ceil($model->count()/10);
		$data=$model->limit(10)->offset(10*$page)->orderBy("sort_order asc")->all();
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('_list_item',['models'=>$data,'new_category'=>$new_category]);
		}else{
			return $this->render('index',['model'=>$data,'page_count'=>$page_count,'new_category'=>$new_category]);
		}
	}
}