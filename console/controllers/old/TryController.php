<?php

namespace console\controllers\old;
use api\models\V1\Order;
use api\models\V1\OrderHistory;
use api\models\V1\OrderPayment;
use api\models\V1\OrderProduct;
use api\models\V1\OrderProductGroup;
use api\models\V1\OrderMerge;
use api\models\V1\OrderShipping;
use api\models\V1\OrderStatus;
use api\models\V1\OrderTotal;
use api\models\V1\Product;
use api\models\V1\ClubTry;
use api\models\V1\ClubTryCoupon;
use api\models\V1\ClubTryUser;
use common\component\Helper\OrderSn;
use common\component\Message\Sms;
use yii\helpers\Json;

class TryController extends \yii\console\Controller
{
	public function actionCreateorder(){
		$result = ClubTryUser::find()->where(['status'=>1,'order_id'=>null])->all();
		if(!is_null($result)){
			$transaction=\Yii::$app->db->beginTransaction();
			try{
			foreach ($result as $key => $value) {
				$Order_model = new Order();
				$Order_model->order_no = OrderSn::generateNumber();
				$Order_model->order_type_code = $value->try->product->bepresell == 1?'presell':'normal';
				$Order_model->platform_id = 1;
				$Order_model->platform_name = "智慧生活";
				$Order_model->platform_url = "http://www.365jiarun.com/";
				$Order_model->store_id = 1;
				$Order_model->customer_group_id = 1;
				$Order_model->customer_id = $value->customer_id;
				$Order_model->firstname = $value->customer->firstname;
				$Order_model->lastname = '';
				$Order_model->email = $value->customer->email;
				$Order_model->telephone =$value->customer->telephone;
				$Order_model->gender = $value->customer->gender;
				$Order_model->payment_method = "免费试";
				$Order_model->payment_code = "TryFree";
				$Order_model->total = 0;
				$Order_model->comment = "试吃活动订单";
				$Order_model->order_status_id = 2;
				$Order_model->ip = '127.0.0.1';
				$Order_model->user_agent = 'SYSTEM';
				$Order_model->accept_language = 'SYSTEM';
				$Order_model->date_added = date("Y-m-d H:i:s", time());
                $Order_model->date_modified = date("Y-m-d H:i:s", time());
				$Order_model->invoice_temp = "不需要发票";
				$Order_model->sent_to_erp = "N";
				if (!$Order_model->save(false)) {
				    throw new \Exception("订单数据异常");
				}else{
					$value->order_id = $Order_model->order_id;
					$value->save();
				}
				$Order_Shipping = new OrderShipping();
				$Order_Shipping->order_id = $Order_model->order_id;
				$Order_Shipping->shipping_firstname = $value->shipping_name;
				$Order_Shipping->shipping_telephone = $value->shipping_telephone;
				$Order_Shipping->shipping_address_1 = $value->address;
				$Order_Shipping->shipping_postcode = $value->postcode;
				$Order_Shipping->shipping_country = '中国';
				$Order_Shipping->shipping_country_id = 854;
				$Order_Shipping->shipping_country_code = 156;
				$Order_Shipping->shipping_zone ='山东省';
				$Order_Shipping->shipping_zone_id = 119;
				$Order_Shipping->shipping_zone_code = 37;
				$Order_Shipping->shipping_city = '青岛市';
				$Order_Shipping->shipping_city_id = 10848;
				$Order_Shipping->shipping_city_code = 3702;
				$Order_Shipping->shipping_district =  $value->district?$value->district->name: "市辖区";
				$Order_Shipping->shipping_district_id = $value->district_id;
				$Order_Shipping->shipping_district_code =$value->district?$value->district->code:"";
				$Order_Shipping->shipping_method = "免运费";
				$Order_Shipping->shipping_code = "free.free";
				$Order_Shipping->delivery_code ='limit';
				$Order_Shipping->delivery =date('Y-m-d',time()+3600*24);
				$Order_Shipping->delivery_time ='08:00-13:00';
				$Order_Shipping->is_delivery = 1;
				if (!$Order_Shipping->save(false)) {
				    throw new \Exception("订单收货地址异常");
				}
				$Order_product = new OrderProduct();
				$Order_product->order_id = $Order_model->order_id;
				$Order_product->product_base_id = $value->try->product->product_base_id;
				$Order_product->product_base_code = $value->try->product->product_base_code;
				$Order_product->product_id = $value->try->product->product_id;
				$Order_product->product_code = $value->try->product->product_code;
				$Order_product->model = 'default';
				$Order_product->name = $value->try->product->description->name;
				$Order_product->sku_name = $value->try->product->getSku();
				$Order_product->quantity = 1;
				$Order_product->price = 0;
				$Order_product->total = 0;
				$Order_product->pay_total=0;
				$Order_product->tax = 0;
				$Order_product->reward = $value->try->product->points;
				$Order_product->unit = $value->try->product->unit;
				$Order_product->format = $value->try->product->format;
				$Order_product->remark = '';
				if (!$Order_product->save(false)) {
				    throw new \Exception("商品创建失败");
				}
                $order_total=[];
				$order_total[] = ['code'=>'sub_total','title'=>'商品总额','value'=>0,'sort_order'=>1];
				$order_total[] = ['code'=>'shipping','title'=>'固定运费','value'=>0,'sort_order'=>3];
				$order_total[] = ['code'=>'total','title'=>'订单总计','value'=>0,'sort_order'=>9];

				foreach ($order_total as $total) {
					$Order_total = new OrderTotal();
					$Order_total->order_id = $Order_model->order_id;
					$Order_total->code = $total['code'];
					$Order_total->title = $total['title'];
					$Order_total->text = '￥' . $total['value'];
					$Order_total->value = $total['value'];
					$Order_total->sort_order = $total['sort_order'];
					if (!$Order_total->save(false)) {
					    throw new \Exception("订单小计异常");
					}
				}

				$Order_history = new OrderHistory();
				$Order_history->order_id = $Order_model->order_id;
				$Order_history->order_status_id = 2;
				$Order_history->comment = '订单提交成功';
				$Order_history->date_added = date('Y-m-d H:i:s', time());
				if (!$Order_history->save(false)) {
				    throw new \Exception("订单记录异常");
				}


				$Order_merge=new OrderMerge();
				$Order_merge->merge_code=OrderSn::generateNumber();;
				$Order_merge->order_ids=$Order_model->order_id;
				$Order_merge->total=0;
				$Order_merge->payment_code='TryFree';
				$Order_merge->customer_id=$value->customer_id;
				$Order_merge->status=1;
				$Order_merge->type_id=0;
				$Order_merge->date_added=date("Y-m-d H:i:s",time());
				if (!$Order_merge->save(false)) {
				    throw new \Exception("支付记录异常");
				}
				$Order_model->merge_code = $Order_merge->merge_code;
				$Order_model->save();


				$Order_payment = new OrderPayment();
				$Order_payment->order_id = $Order_model->order_id;
				$Order_payment->customer_id = $value->customer_id;
				$Order_payment->payment_method = '免费试';
				$Order_payment->payment_code = 'TryFree';
				$Order_payment->total = 0;
				$Order_payment->date_added = date('Y-m-d H:i:s', time());
				if (!$Order_payment->save(false)) {
				    throw new \Exception("支付记录异常");
				}

			}//end foreach
			$transaction->commit();
			}catch (\Exception $e){
			    $transaction->rollBack();
			    die($e->getMessage());
			}
		}//endif
        echo "生成订单结束";
	}
	public function actionTryLottery(){
		$try_list = ClubTry::find()->where(['and', 'lottery_status=0','end_datetime <= NOW()'])->orderBy('quantity desc')->all();
		if(!empty($try_list)){
			$user_recived=[];
			$recived_users_model=ClubTryUser::find()->select('customer_id,count(*) as total')->where('status=1')->groupBy('customer_id')->having('total>2')->all();
			if($recived_users_model){
				foreach($recived_users_model as $user){
					$user_recived[]=$user->customer_id;
				}
			}
			$user_recived=array_unique($user_recived);
			foreach($try_list as $try){
				$telephone=[];
				$message="";
				for($i = 0;$i < $try->quantity;$i++){
					$try_users = ClubTryUser::find()->where(['try_id'=>$try->id,'status'=>0])->all(); //还未中奖的所有用户
					if(!empty($try_users)){
						$try_user_ids = array();
						foreach($try_users as $try_user){
							if(!in_array($try_user->customer_id,$user_recived)){
								$try_user_ids[] = $try_user->id;
							}
						}

						if(count($try_user_ids)>0){
							shuffle($try_user_ids); //打乱数组顺序
							$k = $this->get_rand($try_user_ids);
							$try_user_id = $try_user_ids[$k];
						}else{
							$defauto=ClubTryUser::findOne(['try_id'=>$try->id,'status'=>0]);
							$try_user_id=$defauto?$defauto->id:0;
						}
						if($try_user_id){
							$user = ClubTryUser::findOne($try_user_id);
							$user->status = 1;
							$user->save();
							$user_recived[]=$user->customer_id;
							$title=$try->product->description->name;
							$telephone[]=$user->shipping_telephone;
							$message="恭喜您获得".$title."试吃机会,感谢您的参与，记得发布体验哦";
						}
					}
				}
				$try->lottery_status = 1;
				$try->save();
				if($telephone && $message){
					@Sms::send_notice(implode(",",$telephone),$message);
				}
			}
		}
        echo "抽奖结束";
	}
	public function get_rand($proArr) {
		$result = '';
		//概率数组的总概率精度
		$proSum = array_sum($proArr);
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);
		return $result;
	}
}