<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/4/29
 * Time: 14:27
 */
namespace api\models\V1;
use common\component\Helper\Encrypt3des;
use common\component\Helper\RandomString;
use common\component\Notice\WxNotice;
use common\models\User;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\Model;
use Yii;
use yii\httpclient\Client;
use yii\log\Logger;

class CheckoutOrder extends Model {
	public $out_trade_no;
	public $transaction_id;
	public $payment_method;
	public $payment_code;
	public $staus;
	public $remak = "";

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['out_trade_no', 'filter', 'filter' => 'trim'],
			['out_trade_no', 'required'],
			['transaction_id', 'required'],
			['payment_method', 'required'],
			['payment_code', 'required'],
			['staus', 'required'],
		];
	}

	public function save()
	{
		$transaction = \Yii::$app->db->beginTransaction();
		try {
			if ($model = OrderMerge::findOne(['merge_code' => $this->out_trade_no, 'status' => 0])) {
				$model->status = 1;
				$model->payment_code = $this->payment_code;
				$model->remark = $this->remak;
				$model->date_modified = date('Y-m-d H:i:s', time());
				if (!$model->save(false)) {
					throw new \Exception("合并交易表更新失败");
				}
				$order_ids = explode(",", $model->order_ids);
				foreach ($order_ids as $order_id) {
					if ($order = Order::findOne(['order_id' => $order_id])) {
						$payment = new OrderPayment();
						$payment->order_id = $order_id;
						$payment->customer_id = $order->customer_id;
						$payment->payment_deal_no = $this->transaction_id;
						$payment->payment_method = $this->payment_method;
						$payment->payment_code = $this->payment_code;
						$payment->total = $order->total;
						$payment->remark = $this->remak;
						$payment->date_added = date("Y-m-d H:i:s", time());
						if (!$payment->save(false)) {
							throw new \Exception("支付信息增加失败");
						}
						$order->payment_deal_no = $this->transaction_id;
						$order->payment_method = $this->payment_method;
						$order->payment_code = $this->payment_code;
						$order->merge_code = $this->out_trade_no;
						$order->order_status_id = $this->staus;
						$order->date_modified = date('Y-m-d H:i:s', time());
						if (!$order->save(false)) {
							throw new \Exception("订单主表更新失败");
						}
						$order_log = new OrderHistory();
						$order_log->order_id = $order_id;
						$order_log->order_status_id = $this->staus;
						$order_log->comment = "订单状态更新";
						$order_log->date_added = date('Y-m-d H:i:s', time());
						if (!$order_log->save(false)) {
							throw new \Exception("订单日志增加失败");
						}
						//处理特殊订单小计数据处理
						if ($order_total = OrderTotal::findOne(['order_id' => $order_id, 'code' => 'coupon'])) {
							if ($customer_coupon = CustomerCoupon::findOne(['customer_coupon_id' => $order_total->customer_code_id])) {
								$customer_coupon->is_use = 2;
								$customer_coupon->date_used = date('Y-m-d H:i:s', time());
								if (!$customer_coupon->save(false)) {
									throw new \Exception("用户优惠券折扣更新失败");
								}
							}
						}
						if ($this->staus == 2) {
							if ($order->store_id == 1 && in_array($order->order_type_code, ['normal', 'presell'])) {
//								$rate = 0.02;
//								$low_rate = 0.01;
//								$discount = 0;
//								if ($order->total > 1) {
//									$discount = floatval(number_format(rand($order->total * $low_rate * 100, $order->total * $rate * 100) / 100, 1));
//								} elseif ($order->total == 1) {
//									$discount = floatval(number_format(rand(0.8 * 100, 1.7 * 100) / 100, 1));
//								}
//								if ($discount < 0.1) {
//									$discount = 0.1;
//								}
//								$hongbao = new CustomerHongbao();
//								$hongbao->name = "购物红包";
//								$hongbao->customer_id = $order->customer_id;
//								$hongbao->order_id = $order->order_id;
//								$hongbao->amount = $discount;
//								$hongbao->status = 0;
//								$hongbao->create_at = time();
//								$hongbao->save();
								$prize_chance = new PrizeChance();
								$prize_chance->customer_id = $order->customer_id;
								$prize_chance->order_id = $order->order_id;
								$prize_chance->status = 0;
								$prize_chance->expiration_time = date("Y-m-d H:i:s", time() + 3600 * 24 * 1);
								$prize_chance->date_added = date("Y-m-d H:i:s");
								$prize_chance->save();
							}
							if ($order->order_type_code == 'ACTIVITY') {
								$CodeModel = new OrderScan();
								$CodeModel->order_id = $order->order_id;
								$CodeModel->from_table = 'order';
								$CodeModel->from_table_id = $order->order_id;
								$CodeModel->scan_type = 'ORDER';
								$CodeModel->scan_data = md5($order->order_id . time());
								$CodeModel->scan_key = RandomString::random_text('numeric', 16);;
								$CodeModel->status = 0;
								$CodeModel->date_added = date('Y-m-d H:i:s', time());
								$CodeModel->date_modified = date('Y-m-d H:i:s', time());
								$CodeModel->expire_date = '0000-00-00 00:00:00';
								if (!$CodeModel->save(false)) {
									throw new \Exception("活动凭证订单更新失败");
								}
							}
							if ($order->order_type_code == 'recharge') {
								$odp = OrderDigitalProduct::findOne(['order_id' => $order->order_id]);
								$odp->status = 1;
								if (!$odp->save(false)) {
									throw new \Exception("订单数字产品更新失败");
								}
								if ($odp->type == 'account') {
									$c_t = new CustomerTransaction();
									$c_t->customer_id = $odp->account;
									$c_t->trade_no = $this->out_trade_no;
									$c_t->description = '账户充值|充值金额：' . $order->total . '元';
									$c_t->amount = $order->total;
									$c_t->date_added = date("Y-m-d H:i:s", time());
									if (!$c_t->save(false)) {
										throw new \Exception("用户账户充值失败");
									}
								}
							}
							if ($order_coupons = OrderCoupon::find()->where(['order_id' => $order->order_id, 'status' => 0])->all()) {
								$coupon_msg = [];
								foreach ($order_coupons as $order_coupon) {
									$UserCoupon = new CustomerCoupon();
									$UserCoupon->coupon_id = $order_coupon->coupon_id;
									$UserCoupon->customer_id = $order->customer_id;
									$UserCoupon->is_use = 0;
									$UserCoupon->description = '购物返券';
									if ($order_coupon->coupon->date_type == 'DAYS') {
										$UserCoupon->start_time = date('Y-m-d H:i:s', time());
										$UserCoupon->end_time = date('Y-m-d H:i:s', time() + $order_coupon->coupon->expire_seconds);
									} else {
										$UserCoupon->start_time = $order_coupon->coupon->date_start;
										$UserCoupon->end_time = $order_coupon->coupon->date_end;
									}
									$UserCoupon->date_added = date("Y-m-d H:i:s", time());
									if (!$UserCoupon->save(false)) {
										throw new \Exception("用户优惠券失败");
									}
									$order_coupon->status = 1;
									if (!$order_coupon->save(false)) {
										throw new \Exception("更新优惠券失败");
									}
									$coupon_msg[] = [
										'customer_id' => $order->customer_id,
										'url' => "http://m.mrhuigou.com/user-coupon/index",
										'content' => ['title' => "恭喜您，获得了一张" . $order_coupon->coupon->name, 'total' => $order_coupon->coupon->getRealDiscountName(), 'exp_date' => $UserCoupon->end_time, 'remark' => "在帐户中心优惠券中查看,祝您购物愉快！"]
									];
								}
								if ($coupon_msg) {
									$this->sendCoupon($coupon_msg);
								}
							}
							//新用户首单返现
							$this->fanXian($order);
							$this->sentToZhqd($order);
							$this->points_notice($order);//积分变更通知
						}

					}

				}
				$transaction->commit();
			} else {
				throw new \Exception("【订单信息不存在】:\n" . $this->remak . "\n");
			}
		} catch (\Exception $e) {
			$transaction->rollBack();
			Yii::error("【订单确认】:\n" . $e->getMessage() . "\n");
			return false;
		}
		return true;
	}
	//@params model Order
    private function sentToZhqd($order){
	    //同步订单状态到 智慧青岛
        Yii::error("【zhqd-begin】:\n" . json_encode($order->order_id) . "\n");
        try{
            if($order && $order->affiliate_id == 259 && ($order->order_type_code == 'normal' || $order->order_type_code == 'presell')){
                $order_products = $order->orderProducts;
                $list = [];
                if($order_products){
                    foreach ($order_products as $order_product){
                        $list[] = [
                            'name'=>$order_product->name,
                            'quantity'=>$order_product->quantity,
                            'price' => $order_product->price*100,
                            'total'=> $order_product->total*100,
                        ];
                    }
                }
                $getUrl = 'http://0532.qingdaonews.com/function/life/marketorder';
                $sign_time = time();
                $key = 'ziuppmmve4sb0sv94omuhk1400z95w5d';
                $iv = md5($key . $sign_time);
                $iv = substr($iv, 0, 8);
                $customer_auth = CustomerAuthentication::findOne(['provider' => 'Zhqd', 'customer_id' => $order->customer_id]);
                $token = "";
                if($customer_auth){
                    $token = $customer_auth->identifier;  //本站提供的用户token
                }
                $encrypt = new Encrypt3des();
                $sign = urlencode($encrypt->encode("token=" . $token."&mobile=" . $order->telephone, $key, $iv));
                $parms = [
                    'sign_time' => $sign_time,
                    'sign'  => $sign,
                    'action'=> 'add',
                    'order_id' => $order->order_no,
                    'fee' => $order->total*100,
                    'list'=>json_encode($list)
                ];

                $client = new Client();
                $response = $client->createRequest()
                    ->setMethod('get')
                    ->setUrl($getUrl)
                    ->setData($parms)
                    ->send();
                Yii::error("【zhqd-response】:\n" . json_encode($response) . "\n");
            }
        }catch (ErrorException $e){
            Yii::error("【zhqd-error】:\n" . json_encode($e) . "\n");
            return ;
        }

    }
    private function points_notice($order){
        try{
            if($order->use_points){
                $point_customer_flows = PointCustomerFlow::find()->where(['type'=>'order','type_id'=>$order->order_id])->all();
                if($point_customer_flows){
                    foreach ($point_customer_flows as $point_customer_flow){
                        $point_model = $point_customer_flow->pointCustomer->point;
                        $data['telephone'] = $order->telephone;
                        $data['changeType'] = 2; //1增加 2扣除
                        $data['description'] = '订单消费';
                        $data['orderId'] = $order->order_id;
                        $data['count'] = 1;
                        $data['status'] = 1;
                        $data['creditValue'] = $point_customer_flow->amount;
                        $data['creditValue'] = $point_customer_flow->amount;
                        $data['changeDate'] = date('Y-m-d H:i:s');
                        $data['changeResource'] = 6;
                        $data['point_customer_flow_id'] = $point_customer_flow->point_customer_flow_id;
                        $point_model->notice($data);
                    }
                }
            }
        }catch (ErrorException $e){
            Yii::error("points_notice=============>".$e->getMessage().'at'.$e->getLine());
        }

    }
	public function fanXian($order)
	{
		$message = [];
		if ($order && in_array($order->order_type_code, ['normal', 'presell']) && $order->total > 1) {
			if ($customer_share = CustomerFollower::findOne(['follower_id' => $order->customer_id, 'status' => 0])) {
				$customer_share->status = 1;
				$customer_share->save();
				if ($coupon = Coupon::findOne(['code' => 'ECP161025007'])) {
					$customer_coupon = new CustomerCoupon();
					$customer_coupon->customer_id = $customer_share->customer_id;
					$customer_coupon->coupon_id = $coupon->coupon_id;
					$customer_coupon->description = "好友首单返现红包券";
					$customer_coupon->is_use = 0;
					if ($coupon->date_type == 'DAYS') {
						$customer_coupon->start_time = date('Y-m-d H:i:s', time());
						$customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $coupon->expire_seconds);
					} else {
						$customer_coupon->start_time = $coupon->date_start;
						$customer_coupon->end_time = $coupon->date_end;
					}
					$customer_coupon->date_added = date('Y-m-d H:i:s', time());
					$customer_coupon->save();
					$message[] = [
						'customer_id' => $customer_coupon->customer_id,
						'url' => "http://m.mrhuigou.com/site/index",
						'content' => ['title' => "您的好友[ " . $order->firstname . " ]首次成功下单，恭喜您，获得了5元现金红包", 'name' => "好友首单返现红包", 'content' => "在帐户中心优惠券中查看,祝您购物愉快！"]
					];
				}
			}
		}
		$this->sendMessage($message);
	}



	public function sendCoupon($message)
	{
		if ($message) {
			foreach ($message as $value) {
				if ($user = User::findIdentity($value['customer_id'])) {
					if ($open_id = $user->getWxOpenId()) {
						$notice = new WxNotice();
						$notice->coupon($open_id, $value['url'], $value['content']);
					}
				}
			}
		}
	}

	public function sendMessage($message = [])
	{
		if ($message) {
			foreach ($message as $value) {
				if ($user = User::findIdentity($value['customer_id'])) {
					if ($open_id = $user->getWxOpenId()) {
						$notice = new WxNotice();
						$notice->zhongjiang($open_id, $value['url'], $value['content']);
					}
				}
			}
		}
	}
}