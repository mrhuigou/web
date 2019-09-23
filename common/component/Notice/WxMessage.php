<?php
namespace common\component\Notice;
use common\component\Curl\Curl;
use common\component\Wx\WxSdk;
use yii\helpers\Json;

class WxMessage {

	public function sendText($to,$content){
		$data=[
			'touser'=>$to,
			'msgtype'=>'text',
			"text"=>[
				'content'=>$content
			]
		];
		return $this->send($data);
	}
	public function sendImage($to,$media_id){
		$data=[
			'touser'=>$to,
			'msgtype'=>'image',
			"image"=>[
				'media_id'=>$media_id
			]
		];
		return $this->send($data);
	}

	public function sendVoice($to,$media_id){
		$data=[
			'touser'=>$to,
			'msgtype'=>'voice',
			"voice"=>[
				'media_id'=>$media_id
			]
		];
		return $this->send($data);
	}
	/*
	 * $content =>[[ "title":"Happy Day","description":"Is Really A Happy Day","url":"URL","picurl":"PIC_URL"]]
	 * */
	public function sendNews($to,$content){
		$data=[
			'touser'=>$to,
			'msgtype'=>'news',
			"news"=>[
				'articles'=>$content
			]
		];
		return $this->send($data);
	}
	protected function send($body)
	{
		$appid=\Yii::$app->params['weixin']['appid'];
		$appsecret=\Yii::$app->params['weixin']['appsecret'];
		$wx=new WxSdk($appid,$appsecret);
		$access_token=$wx->getAccessToken();
		$url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
		$curl=new Curl();
		$response=$curl->post($url,Json::encode($body));
		if($response->errcode==0){
			return true;
		}else{
			return false;
		}
	}



}
