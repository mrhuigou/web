<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/14
 * Time: 11:43
 */
namespace h5\widgets\Block;
use yii\bootstrap\Widget;

class Delivery extends Widget {
	public $time;
	public $exclude;
	public $data;
	public $type=1;

	public function init()
	{
//        $this->exclude=[
//            'start_time'=>strtotime('2017-06-03'),
//	        'start_index'=>1,
//            'end_time'=>strtotime('2017-06-06'),
//	        'end_index'=>0
//        ];
		if($this->type){
			$method_times = $this->getList();
			$this->data = [
				'method_times' => $method_times,
				'method_time' => current($method_times)['time'],
				'method_date' => current($method_times)['date'],
			];
		}
	}

	public function getTimes()
	{
		return [
            ['from'=>'11:30','to'=>'23:00','shipping_time'=>'08:00-12:00','shipping_label'=>'早间送','css'=>'green','limit'=>0],
            ['from'=>'23:00','to'=>'24:00','shipping_time'=>'12:00-17:00','shipping_label'=>'午间送','css'=>'org','limit'=>0],
            ['from'=>'00:00','to'=>'11:30','shipping_time'=>'12:00-17:00','shipping_label'=>'午间送','css'=>'org','limit'=>0],
		];
	}

	public function getList()
	{
		$time = time();
		$data = $this->getCurTime($time);
		return $this->getDTimes($data['date'], $data['index']);
	}

	public function getCurTime($time)
	{
		$HourMinute = date("H:i", $time);
		$Date = date("Y-m-d", $time);
		$data = [];
		foreach ($this->getTimes() as $key => $value) {
			if ($HourMinute >= $value['from'] && $HourMinute < $value['to']) {
				if ($HourMinute >= '23:00') {
					$step = 1;
				} else {
					$step = 0;
				}
				$Date = date('Y-m-d', strtotime('+' . $step . ' day', strtotime($Date)));
				$data = ['date' => $Date, 'index' => $key];
				break;
			} else {
				continue;
			}
		}
		return $data;
	}

	public function getDTimes($Date, $index, $delivery_times = [])
	{
		$list = $this->getTimes();
		$value = $list[$index];
		if ($index == 0) {
			$Date = date('Y-m-d', strtotime('+1 day', strtotime($Date)));
		} else {
			$Date = date('Y-m-d', strtotime('+0 day', strtotime($Date)));
		}
		$delivery_data = [];
		if ($this->exclude) {
			$cur_time = strtotime($Date) + $index;
			if ($cur_time >= ($this->exclude['start_time'] + $this->exclude['start_index']) && $cur_time <= ($this->exclude['end_time'] + $this->exclude['end_index'])) {
			} else {
				$delivery_data = ['date' => $Date, 'time' => $value['shipping_time'], 'label' => $value['shipping_label'], 'css' => $value['css'], 'limit' => $value['limit']];
			}
		} else {
			$delivery_data = ['date' => $Date, 'time' => $value['shipping_time'], 'label' => $value['shipping_label'], 'css' => $value['css'], 'limit' => $value['limit']];
		}
		if ($delivery_data = $this->getTimeData($delivery_data)) {
            // 排除10月1
            $restDateArr=['2020-10-01','2020-10-02','2020-10-03'];
            if(!in_array($Date,$restDateArr)){
                $delivery_times[$Date." ".$value['shipping_time']]=$delivery_data;
            }
		}
		if ($index == count($list) - 1) {
			$index = 0;
		} else {
			$index++;
		}
		if (count($delivery_times) == 6) {
			return $delivery_times;
		} else {
			return $this->getDTimes($Date, $index, $delivery_times);
		}
	}

	public function getTimeData($delivery_data)
	{
//		if($delivery_data['limit']){
//			$order_count=Order::find()->alias("o")->joinWith('orderShipping os')->where(['os.delivery'=>$delivery_data['date'],'os.delivery_time'=>$delivery_data['time']])->andWhere(['or',"o.order_status_id=2","o.sent_to_erp='Y'"])->count();
//			if($order_count){
//				if($order_count>=$delivery_data['limit']){
//					return;
//				}
//			}
//		}
		return $delivery_data;
	}

	public function run()
	{
		return $this->render('delivery', ['data' => $this->data,'type'=>$this->type]);
	}
}
