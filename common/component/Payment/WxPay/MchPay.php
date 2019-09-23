<?php
/**
 * 统一支付接口类
 */
namespace common\component\Payment\WxPay;
class MchPay extends Wxpay_client_pub
{
	function __construct()
	{
		//设置接口链接
		$this->url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
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
			$this->parameters["mch_appid"] = WxPayConf_pub::APPID;//公众账号ID
			$this->parameters["mchid"] = WxPayConf_pub::MCHID;//商户号
			$this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];//终端ip
			$this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
			$this->parameters["sign"] = $this->getSign($this->parameters);//签名
			return  $this->arrayToXml($this->parameters);
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	}
	function getResult()
	{
		$this->postXmlSSL();
		$this->result = $this->xmlToArray($this->response);
		return $this->result;
	}
}