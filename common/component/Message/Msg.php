<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/12/22
 * Time: 9:43
 */
namespace common\component\Message;
use yii\helpers\Json;
use yii\httpclient\Client;

class Msg {
	private $AccountSid = "8a48b5515147eb6d01516ba640a8547c";
	private $AccountToken = "ee1854d14de54e56bf75c341f968b2c7";
	private $AppId = "8a48b5515147eb6d01516baa14315484";
	private $HostUrl= "https://app.cloopen.com:8883/2013-12-26";
	private $Batch;  //时间戳
	public function __construct($HostUrl="")
	{
		$this->Batch = date("YmdHis");
		if ($HostUrl) {
			$this->HostUrl = $HostUrl;
		}
	}

	/**
	 * 设置主帐号
	 *
	 * @param AccountSid 主帐号
	 * @param AccountToken 主帐号Token
	 */
	public function setAccount($AccountSid, $AccountToken)
	{
		$this->AccountSid = $AccountSid;
		$this->AccountToken = $AccountToken;
	}

	/**
	 * 设置应用ID
	 *
	 * @param AppId 应用ID
	 */
	public function setAppId($AppId)
	{
		$this->AppId = $AppId;
	}

	/**
	 * 发送模板短信
	 * @param to 短信接收彿手机号码集合,用英文逗号分开
	 * @param datas 内容数据
	 * @param $tempId 模板Id
	 */
	public function sendTemplateSMS($to, $datas, $tempId)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "") {
			return $auth;
		}
		// 拼接请求包体
		$body = [
			'to' => $to,
			'templateId' => $tempId,
			'appId' => $this->AppId,
			'datas' => $datas
		];
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "$this->HostUrl/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = ["Accept"=>"application/json", "Content-Type"=>"application/json;charset=utf-8", "Authorization"=>$authen];
		$http = new Client();
		$response = $http->post($url, Json::encode($body), $header)->send();
		if ($response->isOk) {
			return $response->data;
		} else {
			return null;
		}
	}

	/**
	 * 主帐号鉴权
	 */
	public  function accAuth()
	{
		if ($this->HostUrl == "") {
			$data = new \stdClass();
			$data->statusCode = '172004';
			$data->statusMsg = 'HostUrl为空';
			return $data;
		}
		if ($this->AccountSid == "") {
			$data = new \stdClass();
			$data->statusCode = '172006';
			$data->statusMsg = '主帐号为空';
			return $data;
		}
		if ($this->AccountToken == "") {
			$data = new \stdClass();
			$data->statusCode = '172007';
			$data->statusMsg = '主帐号令牌为空';
			return $data;
		}
		if ($this->AppId == "") {
			$data = new \stdClass();
			$data->statusCode = '172012';
			$data->statusMsg = '应用ID为空';
			return $data;
		}
	}
}