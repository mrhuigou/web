<?php
/**
 * JSAPI支付——H5网页端调起支付接口
 */
namespace common\component\Payment\WxPay;
class JsApi_pub extends Common_util_pub
{
    var $code;//code码，用以获取openid
    var $openid;//用户的openid
    var $parameters;//jsapi参数，格式为json
    var $prepay_id;//使用统一支付接口得到的预支付id
    var $curl_timeout;//curl超时时间

    function __construct()
    {
        //设置curl超时时间
        $this->curl_timeout = WxPayConf_pub::CURL_TIMEOUT;
    }

    /**
     * 	作用：生成可以获得code的url
     */
    function createOauthUrlForCode($redirectUrl,$state="STATE",$scope='snsapi_base',$appid='')
    {
        $urlObj["appid"] = !empty($appid)?$appid:WxPayConf_pub::APPID;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = $scope ? $scope :"snsapi_base";
        $urlObj["state"] = $state."#wechat_redirect";
        $bizString = $this->formatBizQueryParaMap($urlObj, false);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }

    /**
     * 	作用：生成可以获得openid的url
     */
    function createOauthUrlForOpenid($wechat2=false)
    {
        $urlObj["appid"] = WxPayConf_pub::APPID;
        $urlObj["secret"] = WxPayConf_pub::APPSECRET;
        if($wechat2){
            $urlObj["appid"] = WxPayConf_pub::APPID2;
            $urlObj["secret"] = WxPayConf_pub::APPSECRET2;
        }
        $urlObj["code"] = $this->code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->formatBizQueryParaMap($urlObj, false);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }


    /**
     * 	作用：通过curl向微信提交code，以获取openid
     */
    function getOpenid($wechat2=false)
    {
        $url = $this->createOauthUrlForOpenid($wechat2);
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        //取出openid
        $data = json_decode($res,true);
        if(isset($data['openid']) && $data['openid']){
	        $this->openid = $data['openid'];
        }
        return $data;
    }

    /**
     * 	作用：设置prepay_id
     */
    function setPrepayId($prepayId)
    {
        $this->prepay_id = $prepayId;
    }

    /**
     * 	作用：设置code
     */
    function setCode($code_)
    {
        $this->code = $code_;
    }

    /**
     * 	作用：设置jsapi的参数
     */
    public function getParameters($wechat2=false)
    {
        $jsApiObj["appId"] = WxPayConf_pub::APPID;
        if($wechat2){
            $jsApiObj["appId"] = WxPayConf_pub::APPID2;
        }
        $timeStamp = time();
        $jsApiObj["timeStamp"] = "$timeStamp";
        $jsApiObj["nonceStr"] = $this->createNoncestr();
        $jsApiObj["package"] = "prepay_id=$this->prepay_id";
        $jsApiObj["signType"] = "MD5";
        $jsApiObj["paySign"] = $this->getSign($jsApiObj);
        $this->parameters = json_encode($jsApiObj);

        return $this->parameters;
    }
}