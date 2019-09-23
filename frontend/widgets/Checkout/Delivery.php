<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace frontend\widgets\Checkout;
use api\models\V1\Order;
use api\models\V1\Store;
use yii\base\InvalidParamException;
use yii\bootstrap\Widget;
class Delivery extends Widget
{
	public $store_id;
	public $total;
	public $time;
	public $exclude;
	public $data;
	public function init()
	{
		if(!$store=Store::findOne(['store_id'=>$this->store_id])){
			throw new InvalidParamException("参数错误");
		}
        $this->exclude=[
            'start_time'=>strtotime('2017-10-04'),
            'start_index'=>0, //配送时间段，0为早间 1为午间，2为午间 3为晚间
            'end_time'=>strtotime('2017-10-04'),
            'end_index'=>3
        ];
		$method_times=$this->getList($store);
		if(($order_delivery_dates=\Yii::$app->request->post('CheckoutForm')) && isset($order_delivery_dates['delivery'][$store->store_id]['date']) && $order_delivery_dates['delivery'][$store->store_id]['date']){
			$delivery_date=$order_delivery_dates['delivery'][$store->store_id]['date'];
		}else{
			$delivery_date=current($method_times)['date'];
		}
		if(($order_delivery_times=\Yii::$app->request->post('CheckoutForm')) && isset($order_delivery_times['delivery'][$store->store_id]['time']) && $order_delivery_times['delivery'][$store->store_id]['time']){
			$delivery_time=$order_delivery_times['delivery'][$store->store_id]['time'];
		}else{
			$delivery_time=current($method_times)['time'];
		}
		$this->data=[
			'method_times'=>$method_times,
			'method_time'=>$delivery_time,
			'method_date'=>$delivery_date,
			'store_id'=>$this->store_id
		];

		parent::init();
	}

	public function getTimes(){
		return [
			['from'=>'16:00','to'=>'23:00','shipping_time'=>'08:00-13:00','shipping_label'=>'早间送','css'=>'green','limit'=>0],
			['from'=>'23:00','to'=>'24:00','shipping_time'=>'13:00-18:00','shipping_label'=>'午间送','css'=>'org','limit'=>0],
			['from'=>'00:00','to'=>'11:30','shipping_time'=>'13:00-18:00','shipping_label'=>'午间送','css'=>'org','limit'=>0],
			['from'=>'11:30','to'=>'16:00','shipping_time'=>'18:00-22:00','shipping_label'=>'晚间送','css'=>'blue','limit'=>0]
		];
	}
	public function getList($store){
		$time=time();
		if($store->store_code!=='DP0001'){
			$this->time=strtotime(date('Y-m-d', strtotime('+ 1 day',$this->time)));
		}
		$data=$this->getCurTime($time);
		return $this->getDTimes($data['date'],$data['index']);
	}
	public function getCurTime($time){
		$HourMinute=date("H:i",$time);
		$Date=date("Y-m-d",$time);
		$data=[];
		foreach ($this->getTimes() as $key=>$value){
			if($HourMinute>=$value['from'] && $HourMinute<$value['to']){
				if($HourMinute>='23:00'){
					$step=1;
				}else{
					$step=0;
				}
				$Date=date('Y-m-d',strtotime('+'.$step.' day',strtotime($Date)));
				$data=['date'=>$Date,'index'=>$key];
				break;
			}else{
				continue;
			}
		}
		return $data;
	}
	public function getDTimes($Date,$index,$delivery_times=[]){
		$list=$this->getTimes();
		$value=$list[$index];
		if($index==0){
			$Date=date('Y-m-d',strtotime('+1 day',strtotime($Date)));
		}else{
			$Date=date('Y-m-d',strtotime('+0 day',strtotime($Date)));
		}
		$delivery_data=[];
		if($this->exclude){
			$cur_time=strtotime($Date)+$index;
			if($cur_time>=($this->exclude['start_time']+$this->exclude['start_index'])  && $cur_time<=($this->exclude['end_time']+$this->exclude['end_index'])){
			}else{
				$delivery_data=['date'=>$Date,'time'=>$value['shipping_time'],'label'=>$value['shipping_label'],'css'=>$value['css'],'limit'=>$value['limit']];
			}
		}else{
			$delivery_data=['date'=>$Date,'time'=>$value['shipping_time'],'label'=>$value['shipping_label'],'css'=>$value['css'],'limit'=>$value['limit']];
		}
		if($delivery_data=$this->getTimeData($delivery_data)){
			$delivery_times[$Date." ".$value['shipping_time']]=$delivery_data;
		}
		if($index==count($list)-1){
			$index=0;
		}else{
			$index++;
		}
		if(count($delivery_times)==6){
			return $delivery_times;
		}else{
			return $this->getDTimes($Date,$index,$delivery_times);
		}
	}

	public function getTimeData($delivery_data){
		if($delivery_data && $delivery_data['limit']){
			$order_count=Order::find()->alias("o")->joinWith('orderShipping os')->where(['os.delivery'=>$delivery_data['date'],'os.delivery_time'=>$delivery_data['time']])->andWhere(['or',"o.order_status_id=2","o.sent_to_erp='Y'"])->count();
			if($order_count){
				if($order_count>=$delivery_data['limit']){
					return;
				}
			}
		}
		return $delivery_data;
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		return $this->render('delivery',['data'=>$this->data]);
	}
} 