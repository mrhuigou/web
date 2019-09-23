<?php
/**
 * 语音验证码
 * @param verifyCode 验证码内容，为数字和英文字母，不区分大小写，长度4-8位
 * @param playTimes 播放次数，1－3次
 * @param to 接收号码
 * @param displayNum 显示的主叫号码
 * @param respUrl 语音验证码状态通知回调地址，云通讯平台将向该Url地址发送呼叫结果通知
 * @param lang 语言类型。取值en（英文）、zh（中文），默认值zh。
 * @param userData 第三方私有数据
 */
namespace common\component\Sms;
class VoiceVerify {
//主帐号
	public $accountSid = '8a48b5515147eb6d01516ba640a8547c';
//主帐号Token
	public $accountToken = 'ee1854d14de54e56bf75c341f968b2c7';
//应用Id
	public $appId = '8a48b5515147eb6d01516baa14315484';
//请求地址，格式如下，不需要写https://
	public $serverIP = 'app.cloopen.com';
//请求端口
	public $serverPort = '8883';
//REST版本号
	public $softVersion = '2013-12-26';

//voiceVerify("接收号码","验证码内容","循环播放次数","显示的主叫号码","营销外呼状态通知回调地址",'语言类型','第三方私有数据');
	public function send($to,$verifyCode, $playTimes=2,$displayNum='4008556977', $respUrl='', $lang='zh', $userData='')
	{
		// 初始化REST SDK
		$rest = new Rest($this->serverIP, $this->serverPort, $this->softVersion);
		$rest->setAccount($this->accountSid, $this->accountToken);
		$rest->setAppId($this->appId);
		$result = $rest->voiceVerify($verifyCode, $playTimes, $to, $displayNum, $respUrl, $lang, $userData);
		if ($result == NULL) {
			return false;
		}
		if ($result->statusCode != 0) {
			return false;
		} else {
			return true;
		}
	}

}
