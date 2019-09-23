<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/12/8
 * Time: 15:10
 */
namespace h5\widgets\Wx;
use common\component\Wx\WxSdk;
use yii\bootstrap\Widget;

class WeixinSDK extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        $useragent=\Yii::$app->request->getUserAgent();
        if(strpos(strtolower($useragent), 'micromessenger')){
            $jssdk = new WxSdk(\Yii::$app->params['weixin']['appid'], \Yii::$app->params['weixin']['appsecret']);
            $signPackage = $jssdk->GetSignPackage();
            return $this->render('init',['signPackage'=>$signPackage]);
        }
    }
}
