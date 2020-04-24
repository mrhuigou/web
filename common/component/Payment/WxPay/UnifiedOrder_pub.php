<?php
/**
 * 统一支付接口类
 */
namespace common\component\Payment\WxPay;
class UnifiedOrder_pub extends Wxpay_client_pub
{
    function __construct()
    {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //设置curl超时时间
        $this->curl_timeout = WxPayConf_pub::CURL_TIMEOUT;
    }

    /**
     * 生成接口参数xml
     */
    function createXml()
    {
        try
        {
            $this->parameters["appid"] = WxPayConf_pub::APPID;//公众账号ID
            $this->parameters["mch_id"] = WxPayConf_pub::MCHID;//商户号
//            $this->parameters["spbill_create_ip"] = \Yii::$app->request->getUserIP();//终端ip
            $this->parameters["spbill_create_ip"] = explode(',', \Yii::$app->request->getUserIP())[0];//终端ip
            $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
            $this->parameters["sign"] = $this->getSign($this->parameters);//签名
            return  $this->arrayToXml($this->parameters);
        }catch (SDKRuntimeException $e)
        {
            die($e->errorMessage());
        }
    }
	function getWapResult(){
		$this->postXml();
		if($this->response){
			$this->result = $this->xmlToArray($this->response);
			if(isset($this->result['mweb_url']) && $this->result["mweb_url"]){
				return $this->result["mweb_url"];
			}
		}
		return false;
	}
    /**
     * 获取prepay_id
     */
    function getPrepayId()
    {
        $this->postXml();
        if($this->response){
            $this->result = $this->xmlToArray($this->response);
            if(isset($this->result['prepay_id']) && $this->result["prepay_id"]){
                return $this->result["prepay_id"];
            }
        }
        return false;
    }

}