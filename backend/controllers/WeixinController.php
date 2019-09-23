<?php

namespace backend\controllers;
use api\models\V1\WeixinPush;
use backend\models\form\WeixinAccountForm;
use backend\models\form\WeixinSendForm;
use common\component\Curl\Curl;
use Yii;
use yii\httpclient\Client;

class WeixinController extends \yii\web\Controller
{
    public function actionIndex()
    {
	    try{
		    $token = $this->getAccessToken();
		    $url = "https://api.weixin.qq.com/cgi-bin/customservice/getkflist";
		    $http = new Client();
		    $http->setTransport('yii\httpclient\CurlTransport');
		    $header = ["Accept" => "application/json", "Content-Type" => "application/json;charset=utf-8"];
		    $response = $http->get($url, ['access_token'=>$token], $header,['sslVERIFYPEER'=>false,'sslVERIFYHOST'=>false,'CONNECTTIMEOUT'=>0,'NOSIGNAL'=>1])->send();
		    if ($response->isOk) {
			    $result_data=$response->data;
		    }
	    } catch (\Exception $e) {
		    $result_data="network server error";
	    }
        return $this->render('index',['model'=>$result_data]);
    }

    public function actionCreate(){
	    $model = new WeixinAccountForm();
	    if ($model->load(Yii::$app->request->post()) && $reuslt=$model->save()) {
		    return $this->redirect(['index']);
	    } else {
		    return $this->render('create', [
			    'model' => $model,
		    ]);
	    }
    }
	public function actionUpdate(){
		$model = new WeixinAccountForm();
		if ($model->load(Yii::$app->request->post()) && $reuslt=$model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
	public function actionSend(){
		$model=new WeixinSendForm();
		if ($model->load(Yii::$app->request->post()) && $reuslt=$model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
	public function getAccessToken()
	{
		// access_token 应该全局存储与更新，以下代码以写入到文件中做示例
		if (!$access_token = \Yii::$app->cache->get('Wechat_Access_Token')) {
			$appid = \Yii::$app->params['weixin']['appid'];
			$appsecret = \Yii::$app->params['weixin']['appsecret'];
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;
			$http = new Client();
			$http->setTransport('yii\httpclient\CurlTransport');
			$header = ["Accept" => "application/json", "Content-Type" => "application/json;charset=utf-8"];
			$response = $http->get($url, null, $header,['sslVERIFYPEER'=>false,'CONNECTTIMEOUT'=>0,'NOSIGNAL'=>1])->send();
			if ($response->isOk) {
				$access_token = isset($response->data['access_token']) ? $response->data['access_token'] : "";
			} else {
				return $this->getAccessToken();
			}
			if ($access_token) {
				\Yii::$app->cache->set('Wechat_Access_Token', $access_token, 7100);
			}
		}
		return $access_token;
	}
}
