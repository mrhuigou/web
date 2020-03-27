<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/14
 * Time: 8:35
 */
namespace affiliate\controllers;
use api\models\V1\CustomerSharePage;
use common\component\Wx\WxScans;
use yii\base\ErrorException;

class WeixinBrageController extends \yii\web\Controller{
	public function actionIndex(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
		}
		$redirect=\Yii::$app->request->get('redirect');
		if(!$url_param=\Yii::$app->request->get('url_param')){
			return $this->redirect($redirect);
		}
		if(\Yii::$app->user->identity->getSubcription()){
			return $this->redirect($redirect);
		}else{
			$scan=new WxScans();
			$str=[
				'title'=>isset($url_param['title'])?$url_param['title']:'',
				'description'=>isset($url_param['description'])?$url_param['description']:'',
				'pic_url'=>isset($url_param['pic_url'])?$url_param['pic_url']:'',
				'url'=>$redirect,
			];
			$model=$scan->creatScan($str);
			return $this->render('index',['model'=>$model]);
		}
	}
	public function actionShare(){
		try{
//			if (!\Yii::$app->user->isGuest && \Yii::$app->request->isAjax) {
				$title=\Yii::$app->request->post('title');
				$desc=\Yii::$app->request->post('desc');
				$url=\Yii::$app->request->post('link');
				$code=\Yii::$app->request->post('trace_code');
				if(!$model=CustomerSharePage::findOne(['code'=>$code,'customer_id'=>'322'])){
					$model=new CustomerSharePage();
					$model->customer_id='322';
					$model->code=$code;
					$model->title=$title;
					$model->description=$desc;
					$model->url=$url;
					$model->count=1;
					$model->created_at=time();
					$model->save();
				}else{
					$model->count=$model->count+1;
					$model->save();
				}
				$data=['status'=>1,'message'=>"分享成功！"];
//			}
		}catch (ErrorException $e){
			$data=['status'=>0,'message'=>$e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
}