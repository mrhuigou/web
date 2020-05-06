<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/27
 * Time: 9:41
 */
namespace fx\widgets\Product;
use api\models\V1\Address;
use fx\widgets\Checkout\Delivery;
use yii\bootstrap\Widget;
class Shipping extends Widget{
	public $model;
	public function init()
	{
		parent::init();
	}
	public function run(){
        $delivery = new Delivery(['store_id'=>1]);
        $deliver_time = $delivery->data['method_date']." " .$delivery->data['method_time'];
        $deliver_time = "预计".$deliver_time .'送达';
//		if(time()>strtotime(date('Y-m-d 16:00:00',time()))){
//			if(time()<strtotime(date('Y-m-d 23:00:00',time()))){
//				$deliver_time="预计".date('m月d日',time()+60*60*24)." 08:00-13:00 送达";
//			}else{
//				$deliver_time="预计次日(".date('m月d日',time()+60*60*24).") 13:00-18:00 送达";
//			}
//		}else{
//			if(time()<strtotime(date('Y-m-d 11:30:00',time()))){
//				$deliver_time="预计今天(".date('m月d日',time()).") 13:00-18:00 送达";
//			}else{
//				$deliver_time="预计今晚(".date('m月d日',time()).") 18:00-22:00 送达";
//			}
//		}
		if(!\Yii::$app->user->isGuest){
			if(!$address_id=\Yii::$app->session->get('checkout_address_id')){
				$address_id=\Yii::$app->user->identity->address_id;
			}
			if($address=Address::findOne($address_id)){
				return $this->render('shipping',['model'=>$address,'deliver_msg'=>$deliver_time]);
			}
		}else{
			return $this->render('shipping',['model'=>null,'deliver_msg'=>$deliver_time]);
		}
	}
}