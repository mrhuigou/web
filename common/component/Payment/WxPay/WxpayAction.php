<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/4
 * Time: 12:07
 */
namespace common\component\Payment\WxPay;

use Yii;
use yii\base\Action;
use yii\helpers\Url;

class WxpayAction extends Action{
    /**
     * @var array
     */
    public $path="STATE";

    public function init()
    {
        if ($path=Yii::$app->request->get('path')){
            $this->path=$path;
        }
        parent::init();
    }

    public function run()
    {

        //使用jsapi接口
        $jsApi = new JsApi_pub();
        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        $from_affiliate_uid=Yii::$app->session->get('from_affiliate_uid');
        if (!Yii::$app->request->get('code')){
            $host = explode('.', $_SERVER["HTTP_HOST"]);
            if($host[0] == 'fx'){
                $url = $jsApi->createOauthUrlForCode('http://fx.mrhuigou.com/payment/wx-js-call',urlencode($this->path),'snsapi_userinfo');
            }elseif($from_affiliate_uid && $from_affiliate_uid==278){
                $url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL,urlencode($this->path),'snsapi_userinfo',WxPayConf_pub::APPID2);
            }
            else{
                $url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL,urlencode($this->path),'snsapi_userinfo');
            }
//            $url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL,urlencode($this->path),'snsapi_userinfo');
              return Yii::$app->response->redirect($url);
        }else{
            //获取code码，以获取openid
            $code =Yii::$app->request->get('code');
            $state=Yii::$app->request->get('state');
            $jsApi->setCode($code);
            if($from_affiliate_uid && $from_affiliate_uid==278){
                $wechat2=true;
                $data= $jsApi->getOpenId($wechat2);
            }else{
                $data= $jsApi->getOpenId();
            }

            if($data && isset($data['openid'])){
	            Yii::$app->session->set('open_id',$data['openid']);
	            if(isset($data['unionid']) && !empty($data['unionid'])){
                    Yii::$app->session->set('union_id',$data['unionid']);
                }
//	            Yii::$app->session->set('union_id',$data['unionid']);
            }
            return Yii::$app->response->redirect(urldecode($state));
        }
    }
}