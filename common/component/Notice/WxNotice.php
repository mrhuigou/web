<?php
namespace common\component\Notice;

class WxNotice {
	public function pay($to,$url,$message)
	{
	    //已申请模板2019-12-25
//		$template_id = "LoReehuDPcpcBT-ctBreshLyX0dz-dU1FrHA0LswWr8";
//		$data = [
//			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
//			'orderMoneySum' => ['value' => isset($message['total'])?$message['total']:'','color'=>'#173177'],
//			'orderProductName' => ['value' => isset($message['name'])?$message['name']:'','color'=>'#173177'],
//			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
//		];
        $template_id = "wtKG8XasYSH0ATGGh53BgX84DWETm4mBCr32y1ACO8w";
        $data = [
			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
			'keyword1' => ['value' => isset($message['total'])?$message['total']:'','color'=>'#173177'],
			'orderProductName' => ['value' => isset($message['name'])?$message['name']:'','color'=>'#173177'],
			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
		];
		return $this->send($to,$template_id,$url,$data);
	}
	public function no_pay($to,$url,$message)
	{  //已申请模板2019-12-25
		$template_id = "iDcSt2m4FdxMjHID8zRvImCmjckXwI_mqTEzHG3NNfg";
		$data = [
			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
			'ordeID' => ['value' => isset($message['order_no'])?$message['order_no']:'','color'=>'#173177'],
			'ordertape' => ['value' => isset($message['order_date'])?$message['order_date']:'','color'=>'#173177'],
			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
		];
		return $this->send($to,$template_id,$url,$data);
	}
	 public function order($to,$url,$message)
	{  //已申请模板2019-12-25
//		$template_id = "LoReehuDPcpcBT-ctBreshLyX0dz-dU1FrHA0LswWr8";
//		$data = [
//			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
//			'OrderSn' => ['value' => isset($message['order_no'])?$message['order_no']:'','color'=>'#173177'],
//			'OrderStatus' => ['value' => isset($message['status'])?$message['status']:'','color'=>'#173177'],
//			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
//		];
        $template_id = "ZU1CfmLOIKx8lny9N4xV12ztvZuMHMBk9aObLxU16CE";
        $data = [
            'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
            'keyword1' => ['value' => '订单编号 '.isset($message['order_no'])?$message['order_no']:'','color'=>'#173177'],
            'keyword2' => ['value' => isset($message['total'])?$message['total']:'','color'=>'#173177'],
            'keyword3' => ['value' => isset($message['status'])?$message['status']:'','color'=>'#173177'],
            'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
        ];
		return $this->send($to,$template_id,$url,$data);
	}
	public function party($to,$url,$message){
		$template_id = "R9b8y-oZ7eGJxL3Wcx3mK8qIkgNt2eXsytm19Hwd8jw";
		$data = [
			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
			'keynote1' => ['value' => isset($message['name'])?$message['name']:'','color'=>'#173177'],
			'keynote2' => ['value' => isset($message['date_time'])?$message['date_time']:'','color'=>'#173177'],
			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
		];
		return $this->send($to,$template_id,$url,$data);
	}
	public function paytyExp($to,$url,$message){
		$template_id="5u0WptS6P5y9Iy7Y4L80fysAN0iEqD_xIP0fquFV1ic";
		$data=[
			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
			'keyword1' => ['value' => isset($message['name'])?$message['name']:'','color'=>'#173177'],
			'keyword2' => ['value' => isset($message['date_time'])?$message['date_time']:'','color'=>'#173177'],
			'keyword3' => ['value' => isset($message['special'])?$message['special']:'','color'=>'#173177'],
			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
		];
		return $this->send($to,$template_id,$url,$data);
	}
	public function zhongjiang($to,$url,$message){
        //已申请模板2019-12-25
		$template_id = "D0sgE_N4uXlV3cfD7D4gq_sre_r22kvPH59Lk3TlqHw";//公用的模板
		$data = [
			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
			'keyword1' => ['value' => isset($message['name'])?$message['name']:'','color'=>'#173177'],
			'keyword2' => ['value' => isset($message['content'])?$message['content']:'','color'=>'#173177'],
            'keyword3' => ['value' => '无','color'=>'#173177'],
			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
		];
		return $this->send($to,$template_id,$url,$data);
	}
	 public function coupon($to,$url,$message)
	{
		$template_id = "OOm9ETNc7p_STzA4ZVZOt2NHVI0ugSXhwM4M4o1q_mk";
		$data = [
			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#ff0000'],
			'coupon' => ['value' => isset($message['total'])?$message['total']:'','color'=>'#ff0000'],
			'expDate' => ['value' => isset($message['exp_date'])?$message['exp_date']:'','color'=>'#ff0000'],
			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
		];
		return $this->send($to,$template_id,$url,$data);
	}
	 public function exp($to,$url,$message)
	{
		$template_id = "VuG8rmsUhTBX345cyJ44CZv_RLri-7EaKtXM1u9eumM";
		$data = [
			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
			'name' => ['value' => isset($message['name'])?$message['name']:'','color'=>'#173177'],
			'expDate' => ['value' => isset($message['exp_date'])?$message['exp_date']:'','color'=>'#173177'],
			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
		];
		return $this->send($to,$template_id,$url,$data);
	}
	public function reg($to,$url,$message){
		$template_id = "rIkdQaZcaqNYwwDrKMqn1Z4y8Tb9VbZYpVXrosUkz_8";
		$data = [
			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
			'keyword1' => ['value' => isset($message['name'])?$message['name']:'','color'=>'#173177'],
			'keyword2' => ['value' => isset($message['date_time'])?$message['date_time']:'','color'=>'#173177'],
			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
		];
		return $this->send($to,$template_id,$url,$data);
	}
	public function shouhuo($to,$url,$message){
//		$template_id = "o2o1MbrHI_3ja70i_be7W4oXf3XOpiYl8J-aYdeWcjQ";
//		$data = [
//			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
//			'keyword1' => ['value' => isset($message['address'])?$message['address']:'','color'=>'#173177'],
//			'keyword2' => ['value' => isset($message['date_time'])?$message['date_time']:'','color'=>'#173177'],
//			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
//		];
		$template_id = "Mf08ErsIHX4XewY86A7w_uX3WxYIxsiUdPXKmK2yOb4";
        $data = [
            'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
            'keyword1' => ['value' => isset($message['order_no'])?$message['order_no']:'','color'=>'#173177'],
            'keyword2' => ['value' => isset($message['date_time'])?$message['date_time']:'','color'=>'#173177'],
            'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
        ];
		return $this->send($to,$template_id,$url,$data);
	}
	public function translation($to,$url,$message){
		$template_id = "5kfz8G7jzJZEj7dqVoQS7ikEPoK9msLCbDawZcA2QmM";
		$data = [
			'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#173177'],
			'tradeDateTime' => ['value' => isset($message['date'])?$message['date']:'','color'=>'#173177'],
			'tradeType' => ['value' => isset($message['type'])?$message['type']:'','color'=>'#173177'],
			'curAmount' => ['value' => isset($message['amount'])?$message['amount']:'','color'=>'#173177'],
			'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
		];
		return $this->send($to,$template_id,$url,$data);
	}
	public function activecoupon($to,$url,$message){
	    $template_id = 'py5WdEUysuZ69LrWOpsB0bp-gtdozFpY3DV7C2zypk8';
        $data = [
            'first' => ['value' => isset($message['title'])?$message['title']:'','color'=>'#ff0000'],
            'keyword1' => ['value' => isset($message['exp_date'])?$message['exp_date']:'','color'=>'#ff0000'],
            'keyword2' => ['value' => isset($message['nickname'])?$message['nickname']:'','color'=>'#ff0000'],
            'remark' => ['value' =>  isset($message['remark'])?$message['remark']:'如有问题请致电0532-55729957或直接在微信留言，我们将第一时间为您服务！','color'=>'#173177']
        ];
        return $this->send($to,$template_id,$url,$data);
    }
	protected function send($to, $template_id, $url, $data)
	{
	    if(time() >= strtotime("2020-01-06 00:00:00")){
            $body = [
                'touser' => $to,
                'template_id' => $template_id,
			    'url' => $url,
                'topcolor' => '#173177',
                'data' => $data
            ];
        }else{
            $body = [
                'touser' => $to,
                'template_id' => $template_id,
//			'url' => $url,
                'topcolor' => '#173177',
                'data' => $data
            ];
        }

		return \Yii::$app->wechat->sendTemplateMessage($body);
	}

}