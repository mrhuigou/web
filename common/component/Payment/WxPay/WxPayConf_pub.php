<?php
/**
* 	配置账号信息
*/
namespace common\component\Payment\WxPay;
class WxPayConf_pub
{
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	const APPID = 'wx538bb8de1b0f1074';
	//受理商ID，身份标识
	const MCHID = '1567541271';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
//	const KEY = 'A6D73B16CBC9A7F940B5A523F7985AE5';
    const KEY = '184a7eb6e4df4a20ce97e9fd345ac322';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = 'dcedb30a7ec916a09a9fc5e82fbbd6f7';
	
	//=======【JSAPI路径设置】===================================
	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	const JS_API_CALL_URL = 'https://m.mrhuigou.com/payment/wx-js-call';
	
	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
//	const SSLCERT_PATH = '/cacert/apiclient_cert.pem';
//	const SSLKEY_PATH = '/cacert/apiclient_key.pem';
    const SSLCERT_PATH = '';
    const SSLKEY_PATH ='';
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = 'https://open.mrhuigou.com/payment/weixin';

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
}
	
?>