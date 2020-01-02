<?php

namespace backend\controllers;
use api\models\V1\Coupon;
use api\models\V1\CustomerCoupon;
use api\models\V1\WeixinPush;
use backend\models\form\WeixinSendForm;
use common\component\Curl\Curl;
use common\models\User;
use yii\data\ActiveDataProvider;
use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\base\ErrorException;

class WeixinPushController extends \yii\web\Controller
{
    public function actionIndex()
    {
	    $dataProvider = new ActiveDataProvider([
		    'query' => WeixinPush::find(),
	    ]);

	    return $this->render('index', [
		    'dataProvider' => $dataProvider,
	    ]);
    }

    public function actionCreate(){
	    $model=new WeixinSendForm();
	    if ($model->load(Yii::$app->request->post()) && $reuslt=$model->save()) {
		    return $this->redirect(['index']);
	    } else {
		    return $this->render('create', [
			    'model' => $model,
		    ]);
	    }
    }
	public function actionUpdate($id){
		$model=new WeixinSendForm($id);
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
	public function actionNoticeCoupon(){
       // echo md5("whoisyourdaddy");exit; d8a0dffec2518e2732844b019ca702b1
	    if(Yii::$app->request->isPost){
	        try{
                if(!(Yii::$app->request->post('pass')) == md5('whoisyourdaddy')){
                    throw new ErrorException('口令错误');
                }
                $type = Yii::$app->request->post('type');
                if($type == 1){
                    if(!$coupons=Yii::$app->request->post('coupons')){
                        throw new ErrorException('请选择优惠券');
                    }
                    $coupon = Coupon::find()->where(['coupon_id'=>$coupons,'status'=>1])->all();
                    if($coupon){
//                        $command = CustomerCoupon::find()->where("unix_timestamp(end_time) - unix_timestamp(NOW()) > 4*60*60 AND unix_timestamp(end_time) - unix_timestamp(NOW()) <= 24 * 60 * 60 AND is_use = 0 AND is_notice = 0  and coupon_id<>21805")->andWhere(['coupon_id'=>$coupons]);
                        $command = CustomerCoupon::find()->where(" is_use = 0 AND is_notice = 0  and coupon_id<>21805")->andWhere(['coupon_id'=>$coupons]);
                    }
                }else{
                    $command = CustomerCoupon::find()->where("unix_timestamp(end_time) - unix_timestamp(NOW()) > 4*60*60 AND unix_timestamp(end_time) - unix_timestamp(NOW()) <= 24 * 60 * 60 AND is_use = 0 AND is_notice = 0 and coupon_id<>21805");
                }
//24小时之内过期的优惠券
                // $command = CustomerCoupon::find()->where("unix_timestamp(end_time) - unix_timestamp(NOW()) > 4*60*60 AND unix_timestamp(end_time) - unix_timestamp(NOW()) <= 24 * 60 * 60 AND is_use = 0 and coupon_id<>21805");

//                echo "---------Start--《" . date('Y-m-d H:i:s') . "》------>\r\n";
//                $per_page = 300;
//                if($page = Yii::$app->request->get('page')){
//                    $start = ($page -1) * $per_page;
//                    $result = $command->limit(300)->all();
//                }else{
//                    $result = $command->all();
//                }
                if ($result = $command->limit(300)->all()) {
                    $template_id = "VuG8rmsUhTBX345cyJ44CZv_RLri-7EaKtXM1u9eumM";
                    $url = 'https://m.mrhuigou.com/user-coupon/index';
                    $count = 0;
                    foreach ($result as $key => $data) {
                        if ($user = User::findIdentity($data->customer_id)) {
                            if ($open_id = $user->getWxOpenId()) {
                                $msg = $this->getMessage("亲，你有张【" . $data->coupon->name . "】优惠券，即将到期，请尽快使用！！！", $data->end_time,$data->coupon->comment?$data->coupon->comment:$data->coupon->getDescription());
                                //$message[] = $msg;
                                $body = [
                                    'touser' =>$open_id,
                                    'template_id' => $template_id,
                                    'url' => $url,
                                    'topcolor' => '#173177',
                                    'data' => $msg
                                ];
                                $response = $this->send($body);
                                echo $data->customer_coupon_id."----" . $key . "---" . $open_id . "---" . Json::encode($response) . "\r\n";
                                if($response['errcode'] == 0){
                                    $data->is_notice = 1;
                                    $data->save();
                                    $count++;
                                }
                            }else{
                                $data->is_notice = 2;
                                $data->save();
                            }
                        }
                    }
                    $json['status'] = true;
                    $json['msg'] = '发送成功，发送数量'.count($count);
                    return json_encode($json);
                }else{
                    $json['status'] = true;
                    $json['msg'] = '符合条件的折扣券为0';
                    return json_encode($json);
                }
            echo "---------《COMPLETE》------>\r\n";
            }catch (ErrorException $e){
                $json['status'] = false;
                $json['msg'] = $e->getMessage();
                return json_encode($json);
            }
        }else{
	        return $this->render('notice-coupon');
        }

    }
    public function actionCouponEspecially(){
        $command = CustomerCoupon::find()->where(["is_use"=>0,"is_notice"=>0,"coupon_id"=>23323])->andWhere(['and','date_added <"2017-12-21"'])->groupBy('customer_id');

//24小时之内过期的优惠券
        // $command = CustomerCoupon::find()->where("unix_timestamp(end_time) - unix_timestamp(NOW()) > 4*60*60 AND unix_timestamp(end_time) - unix_timestamp(NOW()) <= 24 * 60 * 60 AND is_use = 0 and coupon_id<>21805");

//                echo "---------Start--《" . date('Y-m-d H:i:s') . "》------>\r\n";
        if ($result = $command->all()) {
            $template_id = "VuG8rmsUhTBX345cyJ44CZv_RLri-7EaKtXM1u9eumM";
            $url = 'https://m.mrhuigou.com/user-coupon/index';
            $count = 0;
            foreach ($result as $key => $data) {
                if ($user = User::findIdentity($data->customer_id)) {
                    if ($open_id = $user->getWxOpenId()) {
                        $msg = $this->getMessage("亲，你有张【" . $data->coupon->name . "】优惠券，即将到期，请尽快使用！！！", $data->end_time,$data->coupon->comment?$data->coupon->comment:$data->coupon->getDescription());
                        //$message[] = $msg;
                        $body = [
                            'touser' =>$open_id,
                            'template_id' => $template_id,
                            'url' => $url,
                            'topcolor' => '#173177',
                            'data' => $msg
                        ];
                        $response = $this->send($body);
                        echo $data->customer_coupon_id."----" . $key . "---" . $open_id . "---" . Json::encode($response) . "\r\n";
                        if($response['errcode'] == 0){
                            $data->is_notice = 1;
                            $data->save();
                            $count++;
                        }
                    }
                }
            }
            $json['status'] = true;
            $json['msg'] = '发送成功，发送数量'.count($count);
            return json_encode($json);
        }else{
            $json['status'] = true;
            $json['msg'] = '符合条件的折扣券为0';
            return json_encode($json);
        }
    }
    protected function send($body)
    {
        $result_data="";
        try{
            $token = \Yii::$app->wechat->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
            $http = new Client();
            $http->setTransport('yii\httpclient\CurlTransport');
            $header = ["Accept" => "application/json", "Content-Type" => "application/json;charset=utf-8"];
            $response = $http->post($url, Json::encode($body), $header,['sslVERIFYPEER'=>false,'sslVERIFYHOST'=>false,'CONNECTTIMEOUT'=>0,'NOSIGNAL'=>1])->send();
            if ($response->isOk) {
                $result_data=$response->data;
            }
        } catch (\Exception $e) {
            $result_data="network server error";
        }

        return $result_data;
    }
    private function getMessage($title, $exp_date,$desc=null)
    {
        $message = [
            'first' => [
                'value' => $title,
                'color' => '#ff0000'
            ],
            'name' => [
                'value' => "优惠券",
                'color' => '#173177'
            ],
            'expDate' => [
                'value' => $exp_date,
                'color' => '#173177'
            ],
            'remark' => [
                'value' => $desc."\r\n专注同城，现在下单，今日送达！\r\n如有疑问请联系客服，客服热线053255769778。",
                'color' => '#173177'
            ]
        ];
        return $message;
    }
}
