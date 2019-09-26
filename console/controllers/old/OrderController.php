<?php
namespace console\controllers\old;
use api\models\V1\Affiliate;
use api\models\V1\AffiliatePersonal;
use api\models\V1\AffiliateTransaction;
use api\models\V1\AffiliateTransactionFlow;
use api\models\V1\CouponHistory;
use api\models\V1\Customer;
use api\models\V1\CustomerChest;
use api\models\V1\CustomerCommission;
use api\models\V1\CustomerCommissionFlow;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerTransaction;
use api\models\V1\GroundPushPointToCustomer;
use api\models\V1\GroundPushStock;
use api\models\V1\Order;
use api\models\V1\OrderActivity;
use api\models\V1\OrderBlack;
use api\models\V1\OrderCycle;
use api\models\V1\OrderCycleDetail;
use api\models\V1\OrderDeliveryComment;
use api\models\V1\OrderDigitalProduct;
use api\models\V1\OrderGift;
use api\models\V1\OrderHistory;
use api\models\V1\OrderPayment;
use api\models\V1\OrderProduct;
use api\models\V1\OrderProductGroup;
use api\models\V1\OrderScan;
use api\models\V1\OrderShipping;
use api\models\V1\OrderStatus;
use api\models\V1\OrderTotal;
use api\models\V1\Platform;
use api\models\V1\PointCustomerFlow;
use api\models\V1\Product;
use api\models\V1\PromotionDetail;
use api\models\V1\PromotionGroup;
use api\models\V1\PromotionHistory;
use api\models\V1\RechargeHistory;
use api\models\V1\ReturnBase;
use api\models\V1\ReturnHistory;
use api\models\V1\ReturnProduct;
use api\models\V1\ReturnStatus;
use api\models\V1\Store;
use api\models\V1\WarehouseLog;
use api\models\V1\WarehouseStock;
use common\component\Message\Msg;
use common\component\Message\Sms;
use common\component\Notice\WxNotice;
use common\models\User;
use h5\models\ReturnAllForm;
use yii\db\StaleObjectException;
use yii\helpers\Json;
use Yii;
use yii\httpclient\Client;

class OrderController extends \yii\console\Controller {
	public function actionIndex()
	{
		return $this->render('index');
	}

	//获取结果数据方法
	protected function getResult($data)
	{
		$result = Json::decode($data, true);
		return $result;
	}

	//生成请求数据方法
	protected function CreatRequestParams($a, $d = [], $v = '1.0')
	{
		$t = time();
		$m = 'webservice';
		$key = 'asdf';
		$data = ['a' => $a, 'c' => 'NONE', 'd' => $d, 'f' => 'json', 'k' => md5($t . $m . $key), 'm' => $m, 'l' => 'CN', 'p' => 'soap', 't' => $t, 'v' => $v];
		return Json::encode($data);
	}

	//同步订单状态数据方法
	public function actionStatus()
	{
		$client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], ['soap_version' => SOAP_1_1, 'exceptions' => false]);
		try {
			$data = $this->CreatRequestParams('autoOrderStatusForJson');
			$content = $client->getInterfaceForJson($data);
			if (is_soap_fault($content)) {
				throw new \Exception("can not soap url");
			}
			$result = Json::decode($content);
			if (isset($result['data']) && $result['data']) {
				$this->OrderStatus($result['data']);
			}
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
		echo "run time" . date("Y-m-d H:i:s", time());
	}

	//同步订单状态数据
	public function OrderStatus($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		try {
			foreach ($datas as $data) {
				if (strpos($data['CODE'], 'RO') !== false) {
					$return_status = ReturnStatus::findOne(['code' => $data['STATUS']]);
					if ($return_status) {
						if ($model = ReturnBase::findOne(['return_code' => $data['CODE']])) {
							if ($model->return_status_id !== $return_status->return_status_id) {
								$model->date_modified = $data['UPDATETIME'];
								$model->return_status_id = $return_status->return_status_id;
								if (!$model->save()) {
									throw new \Exception(json_encode($model->errors));
								}
								$return_id = $model->return_id;
								$model = new ReturnHistory();
								$model->return_id = $return_id;
								$model->return_status_id = $return_status->return_status_id;
								$model->notify = 0;
								$model->comment = "你的订单状态更新为:" . $return_status->name;;
								$model->date_added = $data['UPDATETIME'];
								if (!$model->save()) {
									throw new \Exception(json_encode($model->errors));
								}
							}
						}
					}
				} else {
					$order_status = OrderStatus::findOne(['code' => trim($data['STATUS'])]);
					if ($order_status) {
						$order_id = trim($data['CODE'], 'O');
						if (strpos($data['CODE'], '-')) {
							list($order_id, $order_cycle_id) = explode('-', $order_id);
							$order_id = intval($order_id);
							if ($model = OrderCycle::findOne(['order_id' => $order_id, 'order_cycle_id' => $order_cycle_id])) {
								if ($model->order_status_id !== $order_status->order_status_id) {
									$model->order_status_id = $order_status->order_status_id;
									$model->save();
								}
							}
						} else {
							if (strpos($order_id, "_")) {
								$order_id = substr($order_id, strpos($order_id, "_"));
							}
							$order_id = intval($order_id);
							if ($model = Order::findOne(['order_id' => $order_id])) {
								if ($model->order_status_id !== $order_status->order_status_id) {
									$model->date_modified = $data['UPDATETIME'];
									$model->order_status_id = $order_status->order_status_id;
									if (!$model->save()) {
										throw new \Exception(json_encode($model->errors));
									}
									$order = $model;
									$model = new OrderHistory();
									$model->order_id = $order_id;
									$model->order_status_id = $order_status->order_status_id;
									$model->notify = 0;
									$model->comment = "你的订单状态更新为:" . $order_status->name;
									$model->date_added = $data['UPDATETIME'];
									if (!$model->save()) {
										throw new \Exception(json_encode($model->errors));
									}
									if ($order->order_status_id == 9) {
										if ($order->orderShipping) {
											if ($open_id = $order->customer->getWxOpenId()) {
												$notice = new WxNotice();
												$message = "亲:您的" . $order->order_no . "订单已发货，家润小哥正赶来，您的一缕微笑是我们更好服务的动力，您可在“账户中心”实时跟踪货物地图位置，祝您购物愉快";
												$notice->order($open_id, "http://m.mrhuigou.com/order/shipping?order_no=" . $order->order_no, ['title' => '尊敬的家润会员', 'order_no' => $order->order_no, 'status' => '已发出', 'remark' => $message]);
											}
											if ($order->orderShipping->shipping_telephone !== $order->telephone) {
												$msg = new Msg();
												$msg->sendTemplateSMS($order->orderShipping->shipping_telephone, [$order->order_no], 144101);
											}
										}
									}
//									if ($order->order_status_id == 10) {
//										if ($open_id = $order->customer->getWxOpenId()) {
//											$message = "感谢您此次购物，您可点击详情对本次服务进行评价！您的评价对物流小哥，很重要哟！祝您购物愉快！";
//											$notice = new WxNotice();
//											$notice->shouhuo($open_id, "https://m.mrhuigou.com/order/delivery?order_no=" . $order->order_no, ['title' => '尊敬的家润会员,您的订单号' . $order->order_no . "已经收货！", 'address' => $order->orderShipping->shipping_address_1, 'date_time' => date('Y-m-d H:i:s', time()), 'remark' => $message]);
//										}
//									}
									if ($order->order_status_id == 11) {
									    if($order->affiliate_id){
                                            $this->OrderCommission($order->order_id);
                                        }else{
                                            $this->Commission($order->order_id);
                                        }


									}
								}
							}
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			echo $e->getMessage();
			throw new \Exception($e->getMessage());
		}
	}
	protected function Commission($order_id)
	{ //来自客户分享的提佣
        if ($order_model = Order::findOne(['order_id' => $order_id])) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if($order_model->current_source_customer_id){
                    $data_left = $order_model->getAffPersonalCommission();

                    if(isset($data_left) && isset($data_left['aff_personal_commision']) && $data_left['aff_personal_commision'] > 0){
                        if(isset($data_left['left_order_products_array'])){
                            $data_mark['type'] = 'part';
                        }else{
                            $data_mark['type'] = 'all';
                        }
                        $data_mark['commission_order_products'] = $data_left['in_order_products_array']; //该次提成的提成商品 记录到mark中方便查找追踪

                        $this->setCustomerCommission($order_model->current_source_customer_id,$data_left['aff_personal_commision'],$order_model->order_id,json_encode($data_mark),'aff_personal');
                    }
                }

                if ($order_model->source_customer_id) {
                    $commissioin = 0;
                    $data_mark = [];
                    Yii::error("source_customer_id=".$order_model->source_customer_id);
                    if(isset($data_left) && isset($data_left['left_order_products_array'])){
                        $left_count = count($data_left['left_order_products_array']);
                        if( $left_count> 0){
                            if($left_count < count($order_model->orderProducts) ){
                                $data_mark['type'] = 'part';
                                $data_mark['commission_order_products'] = array_column($data_left['left_order_products_array'],'product_id');
                                $commissioin = $order_model->getAffCustomerCommision($data_left['left_order_products_array']);
                            }
                        }
                    }else{
                        $data_mark['type'] = 'all';
                        $commissioin = $order_model->getAffCustomerCommision();
                    }
                    Yii::error("commissioin=".$commissioin);
                    if($commissioin > 0){
                        $this->setCustomerCommission($order_model->source_customer_id,$commissioin,$order_model->order_id,json_encode($data_mark),'aff_customer');
                    }
                }

                $transaction->commit();
            } catch (\Exception $e) {
                \Yii::error(json_encode($e->getMessage().' at '.$e->getLine()));
                $transaction->rollBack();
            }
        }
	}
	private function setCustomerCommission($source_customer_id,$amout,$order_id,$remark='',$aff_type='aff_customer'){
	    if($amout > 0){
            if (!$model = CustomerCommission::findOne(['customer_id' => $source_customer_id])) {
                $model = new CustomerCommission();
                $model->customer_id = $source_customer_id;
            }
            $model->amount = $model->amount + floatval($amout);
            if (!$model->save()) {
                throw new \Exception(json_encode($model->errors));
            }
            Yii::error('CustomerCommission json:'.json_encode($model->errors));

            if(!$flow_model = CustomerCommissionFlow::findOne(['type_id'=>'order_id','customer_id'=>$model->customer_id,'aff_type'=>$aff_type])){
                $flow_model = new CustomerCommissionFlow();
                $flow_model->is_notice = 0;
            }
            $flow_model->type="order";
            $flow_model->type_id=$order_id;
            $flow_model->customer_id = $model->customer_id;
            $flow_model->title = "入帐";
            $flow_model->amount = floatval($amout);
            $flow_model->balance = $model->amount;
            $flow_model->remark = "订单收益";
            $flow_model->data_mark = $remark;
            $flow_model->status = 1;
            $flow_model->create_at = time();
            $flow_model->aff_type = $aff_type;
            if(!$flow_model->save()){
                throw new \Exception(json_encode($flow_model->errors));
            }
            Yii::error('CustomerCommissionFlow json:'.json_encode($flow_model->errors));
            if($flow_model->is_notice == 0){
                $template_id = 'i1q1M2mGcTEeFJySynB8FODHTNpwQ7QTGR3zU8SOuHk';//佣金提醒
                $url = 'https://m.mrhuigou.com/user-share/index';
                if ( $user = User::findIdentity($flow_model->customer_id)) {
                    if ($open_id = $user->getWxOpenId()) {
                        $msg = $this->getMessage("您获得了一笔新的佣金，点击消息查看收益情况", $flow_model);
                        $body = [
                            'touser' => $open_id,
                            'template_id' => $template_id,
                            'url' => $url,
                            'topcolor' => '#173177',
                            'data' => $msg
                        ];
                        $result = $this->send($body);
                        if ($result['errcode'] == 0) {
                            $flow_model->is_notice = 1;
                            $flow_model->save();
                        }
                    }
                }
            }
        }
    }
    private function getMessage($title, $data){
        $message = [
            'first' => [
                'value' => $title,
                'color' => '#ff0000'
            ],
            'keyword1' => [
                'value' => $data->amount,
                'color' => '#173177'
            ],
            'keyword2' => [
                'value' => $data->create_at,
                'color' => '#173177'
            ],
            'remark' => [
                'value' => "\r\n专注同城，现在下单，今日送达！\r\n如有疑问请联系客服，客服热线4008556977。",
                'color' => '#173177'
            ]
        ];
        return $message;
    }

    protected function send($body)
    {
        $result_data="";
        try{
            $token = \Yii::$app->wechat->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
            $http = new Client();
            $http->setTransport('yii\httpclient\CurlTransport');
            $header = ["Accept" => "application/json", "Content-Type" => "application/json;charset=utf-8"];
            $response = $http->post($url, Json::encode($body), $header,['sslVERIFYPEER'=>false,'sslVERIFYHOST'=>false,'CONNECTTIMEOUT'=>0,'NOSIGNAL'=>1])->send();
            if ($response->isOk) {
                $result_data=$response->data;
            }
        } catch (\Exception $e) {
            $result_data="network server error";
        }

        return $result_data;
    }
	protected function OrderCommission($order_id)
	{
	    //下单成功订单状态为 交易完成时候，为分销商提供佣金
		if ($order_model = Order::findOne(['order_id' => $order_id])) {
		    if($order_model->affiliate_id ){
                $affiliate = Affiliate::findOne(['affiliate_id'=> $order_model->affiliate_id,'status'=>1]);
                if($affiliate->settle_type == 'order'){
                    echo "order===begin===========";
                    $cash = $order_model->commission;//总提成
                    if ($order_model->affiliate_id && abs($cash) > 0) {
                        $returns = ReturnBase::find()->where(['order_id'=>$order_model->order_id])->andWhere(['<>','return_status_id','6'])->all();
                        if($returns){
                            foreach ($returns as $return){
                                $cash = $cash - bcmul($return->total,$affiliate->commission,2);
                            }
                        }
                        if($cash < 0){
                            $cash = 0;
                        }
                        if($cash > 0){
                            $this->settleAffiliate($order_model,$cash);
                        }
                    }
                }
                if($affiliate->settle_type == 'customer'){
                    echo "customer===begin===========";
                    //佰通卡类似的提成方式---引进新会员首次下单时给与固定金额提成
                    $customer = Customer::findOne(['customer_id'=>$order_model->customer_id]);
                    if($customer->affiliate_id == $affiliate->affiliate_id){
                        echo "customer===yes affiliate_id===========";
                        $order_status_ids = [2,3,5,9,10,11];
                        $order_count = Order::find()->where(['customer_id'=>$order_model->customer_id,'order_status_id'=>$order_status_ids])->andWhere(['and','date_added <"'.$order_model->date_added.'"'])->count();
                        echo "customer===order_count ：".$order_count." ===========";
                        if($order_count<1){
                            if($affiliate->settle_commission = 'F'){
                                $cash = $affiliate->commission; //固定提成
                                $returns = ReturnBase::find()->where(['order_id'=>$order_model->order_id,'is_all_return'=>1])->andWhere(['<>','return_status_id','6'])->count();

                                if($returns){
                                   return false;
                                }else{
                                    echo "customer===cash ：".$cash." ===========";
                                    $this->settleAffiliate($order_model,$cash);
                                }
                            }
                        }
                    }
                }
            }
		}
	}
	private function settleAffiliate($order_model,$cash){
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!$model = AffiliateTransaction::findOne(['affiliate_id' => $order_model->affiliate_id])) {
                $model = new AffiliateTransaction();
                $model->affiliate_id = $order_model->affiliate_id;
                $model->amount = 0;
            }
            $model->amount = $model->amount + floatval($cash);

            if(!$flow_model=AffiliateTransactionFlow::findOne(['type'=>'order','type_id'=>$order_model->order_id])){
                if (!$model->save()) {
                    echo json_encode($model->errors);
                    throw new \Exception(json_encode($model->errors));
                }
                $flow_model = new AffiliateTransactionFlow();
                $flow_model->type="order";
                $flow_model->type_id=$order_model->order_id;
                $flow_model->affiliate_id = $model->affiliate_id;
                $flow_model->amount = floatval($cash);
                $flow_model->title = "入帐";
                $flow_model->balance = $model->amount;
                $flow_model->remark = "订单收益";
                $flow_model->status = 1;
                $flow_model->create_at = time();
                $flow_model->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
            $transaction->rollBack();
        }
    }
		public	function getIsHisense($customer_id)
		{
			$status = false;
			$model = RechargeHistory::find()->where(['customer_id' => $customer_id])->all();
			if ($model) {
				foreach ($model as $value) {
					if ($value->rechargeCard && strtolower($value->rechargeCard->card_code) == strtolower('Hisense')) {
						$status = true;
						break;
					}
				}
			}
			return $status;
		}

		//同步订单数据
		public	function actionAuto()
		{
			echo "begin-time:" . date("Y-m-d H:i:s", time()) . "\n";
			$order_datas = Order::find()->Where(['sent_to_erp'=>"N"])->andWhere(['not in','order_type_code',['order_cycle','ACTIVITY']])->andWhere(['and','order_status_id in (2,13,10)'])->orderBy(['order_id' => 'SORT_ASC'])->limit(100)->all();
			$input_data = [];
			foreach ($order_datas as $key => $order) {
				$scan = [];
				//订单基础数据
                $comment = "";//附加的comment订单备注
				if ($this->getIsHisense($order->customer_id) && $order->payment_code == 'balance') {
					$comment = "<海信>";
				}
				if(strtolower($order->order_type_code) == strtolower('GroundPush')){
				    $ground_push_point_customer = GroundPushPointToCustomer::findOne(['order_id'=>$order->order_id]);
				    if($ground_push_point_customer){
                        if($ground_push_point_customer->point){
                            $comment = "<".$ground_push_point_customer->point->name.">" ;
                        }
                    }


                }
                $order->comment = $comment. ' '.$order->comment;
				$order_base_data = [
					'CODE' => "O" . $order->order_id,
					'PARENTCODE' => $order->parent_id ? "O" . $order->parent_id : '',
					'ORDERDATE' => $order->date_modified ? $order->date_modified : date('Y-m-d H:i:s', time()),
					'SOURCE' => $order->user_agent == 'APP' ? 'APP' : "WEB",
					'PLATFORMCODE' => $order->platform ? $order->platform->platform_code : '',
					'TYPE' => strtoupper($order->order_type_code),
					'RETURNTYPE' => '',
					'MEMBERCODE' => $order->customer_id,
					'DESCRIPTION' => $order->comment,
					'RELATEDBILL1' => '',
					'RELATEDBILL2' => '',
					'SHOPCODE' => $order->store ? $order->store->store_code : '',
					'SCANS' => $scan,
                    'USEPOINTS'=>$order->use_points ? true : false,
				];
				//订单配送信息
				if ($order->orderShipping) {
					if (in_array($order->orderShipping->shipping_district_id, ['7613', '7610'])) {
						$order->orderShipping->shipping_address_1 = $order->orderShipping->shipping_city . $order->orderShipping->shipping_district . $order->orderShipping->shipping_address_1;
					}
					$order_shipping_data = [
						'CONTACTNAME' => $order->orderShipping->shipping_firstname,
						'TELEPHONE' => $order->orderShipping->shipping_telephone,
						'MOBILE' => $order->telephone ? $order->telephone : $order->orderShipping->shipping_telephone,
						'CITY' => $order->orderShipping->shipping_city,
						'ADDRESS' => $order->orderShipping->shipping_address_1,
						'DELIVERY_TYPE' => strtoupper($order->orderShipping->delivery_code),
						'DELIVERY_VALUE' => $order->orderShipping->delivery,
						'DELIVERY_VALUE2' => $order->orderShipping->delivery_time,
						'DELIVERY_STATION_CODE' => $order->orderShipping->station_code,
						'BEDELIVERY' => $order->orderShipping->is_delivery ? true : false,
					];
				} else {
					$order_shipping_data = [
						'CONTACTNAME' => "",
						'TELEPHONE' => "",
						'MOBILE' => "",
						'CITY' => "",
						'ADDRESS' => "",
						'DELIVERY_TYPE' => "",
						'DELIVERY_VALUE' => "",
						'DELIVERY_VALUE2' => '',
						'DELIVERY_STATION_CODE' => '',
						'BEDELIVERY' => false,
					];
				}
				//订单支付数据
//                if($order->use_points){
//                    $points_rmb = 0;
//                    if ($order->orderTotals) {
//                        foreach ($order->orderTotals as $order_total) {
//                            if($order_total->code =='points'){
//                                $points_rmb = bcadd($points_rmb,$order_total->value,4);
//                            }
//                        }
//                        //$points_rmb = round($points_rmb,2);
//                    }
//                    $payment_total = round(bcadd($order->total,$points_rmb,4)); //实付金额，应当算上积分抵扣的
//                    $order_payment_data = [
//                        'PAYTYPECODE' => strtoupper($order->payment_code),
//                        'PAYTYPE' => $order->payment_method,
//                        'PAYMENT' => abs($payment_total),
//                        'SERIAL_NUMBER' => $order->payment_deal_no,
//                    ];
//
//                }else{
                    $order_payment_data = [
                        'PAYTYPECODE' => strtoupper($order->payment_code),
                        'PAYTYPE' => $order->payment_method,
                        'PAYMENT' => $order->total,
                        'SERIAL_NUMBER' => $order->payment_deal_no,
                    ];
//                }

				//订单发票信息
				$order_invoice = ['0' => '不需要发票', '1' => '个人发票','2'=>'企业增值税专票', '3' => '企业增值税普票'];
				$invoice_type = array_search($order->invoice_temp, $order_invoice);

				if(strpos($order->invoice_title,"|")){
                    if($invoice_type == 2){
                        list($a,$b,$c,$d)=explode("|",$order->invoice_title);
                    }else{
                        list($a,$b)=explode("|",$order->invoice_title);
                        $c = '--';
                        $d = '--';
                    }
				}else{
				    if($invoice_type == 1){
				        $a = $order->invoice_title;
				        $b = '--';
                        $c = '--';
                        $d = '--';
                    }else{
                        list($a,$b,$c,$d)=["---","---","---","---"];
                    }
				}
				$order_invoice_data = [
					'INVOICE_TYPE' => $invoice_type,
					'INVOICE_NAME' => $a,
					'INVOICE_COMPANY' => $a,
					'INVOICE_TAX_NUMBER' =>$b,
					'INVOICE_ADD_TEL' => $c,
					'INVOICE_BANK' => $d,
				];
				$orderdata = array_merge($order_base_data, $order_shipping_data, $order_payment_data, $order_invoice_data);
				////订单商品信息
				$details = [];
				$lineno = 1;
				if ($order->orderProducts) {
					foreach ($order->orderProducts as $OrderProduct) {
						$details[] = [
							'ORDERCODE' => "O" . $order->order_id,
                            'TYPE' => 'product',
							'LINENO' => $lineno,
							'SHOPCODE' => $order->store ? $order->store->store_code : '',
							'PRODUCTCODE' => $OrderProduct->product_base_code,
							'PUCODE' => $OrderProduct->product_code,
							'QUANTITY' => $OrderProduct->quantity,
							'PRICE' => $OrderProduct->price,
							'AMOUNT' => $OrderProduct->total,
							'PAYMENT'=>$OrderProduct->pay_total,
							'DESCRIPTION' => $OrderProduct->remark ? $OrderProduct->remark : '',
							'PROMOTIONDETAILCODE' => $OrderProduct->promotionDetail ? $OrderProduct->promotionDetail->promotion_detail_code : '',
							'SCANS' => [],
							'COUPONCODE' => '',
						];
						$lineno++;
						if ($OrderProduct->gift) {
							foreach ($OrderProduct->gift as $gift) {
								$details[] = [
									'ORDERCODE' => "O" . $order->order_id,
                                    'TYPE' => 'product',
									'LINENO' => $lineno,
									'SHOPCODE' => $order->store ? $order->store->store_code : '',
									'PRODUCTCODE' => $gift->product_base_code,
									'PUCODE' => $gift->product_code,
									'QUANTITY' => $gift->quantity,
									'PRICE' => $gift->price,
									'AMOUNT' => $gift->total,
									'PAYMENT'=>0,
									'DESCRIPTION' => '商品赠品',
									'PROMOTIONDETAILCODE' => $gift->promotionDetail ? $gift->promotionDetail->promotion_detail_code : '',
									'SCANS' => [],
									'COUPONCODE' => '',
								];
								$lineno++;
							}
						}
					}
				}
				//订单赠品信息
				if ($order->orderGifts) {
					foreach ($order->orderGifts as $gift) {
						$details[] = [
							'ORDERCODE' => "O" . $order->order_id,
                            'TYPE' => 'product',
							'LINENO' => $lineno,
							'SHOPCODE' => $order->store ? $order->store->store_code : '',
							'PRODUCTCODE' => $gift->product_base_code,
							'PUCODE' => $gift->product_code,
							'QUANTITY' => $gift->quantity,
							'PRICE' => $gift->price,
							'AMOUNT' => $gift->total,
							'PAYMENT'=>0,
							'DESCRIPTION' => '订单赠品',
							'PROMOTIONDETAILCODE' => $gift->promotionOrderDetail ? $gift->promotionOrderDetail->promotion_order_code : '',
							'SCANS' => [],
							'COUPONCODE' => '',
						];
						$lineno++;
					}
				}
				//数字产品信息
				if ($order->orderDigitalProducts) {
					foreach ($order->orderDigitalProducts as $digital) {
						$orderdata = array_merge($orderdata, ['TYPE' => strtoupper($order->order_type_code . "-" . $digital->type)]);
						$details[] = [
							'ORDERCODE' => "O" . $order->order_id,
                            'TYPE' => 'product',
							'LINENO' => $lineno,
							'SHOPCODE' => '',
							'PRODUCTCODE' => '',
							'PUCODE' => '',
							'QUANTITY' => $digital->qty,
							'PRICE' => $digital->price,
							'AMOUNT' => $digital->total,
							'PAYMENT'=>$digital->total,
							'DESCRIPTION' => $digital->code . "-" . $digital->name . "-" . $digital->account,
							'PROMOTIONDETAILCODE' => '',
							'SCANS' => [],
							'COUPONCODE' => '',
						];
						$lineno++;
					}
				}
				//服务类商品
				if ($activity = $order->activity) {
					$details[] = [
						'ORDERCODE' => "O" . $order->order_id,
                        'TYPE' => 'product',
						'LINENO' => $lineno,
						'SHOPCODE' => '',
						'PRODUCTCODE' => '',
						'PUCODE' => '',
						'QUANTITY' => $activity->quantity,
						'PRICE' => $activity->price,
						'AMOUNT' => $activity->total,
						'PAYMENT'=>$activity->total,
						'DESCRIPTION' => $activity->activity_name . "-" . $activity->activity_item_name,
						'PROMOTIONDETAILCODE' => '',
						'SCANS' => [],
						'COUPONCODE' => '',
					];
					$lineno++;
				}
				//订单总计信息
				if ($order->orderTotals) {
					foreach ($order->orderTotals as $order_total) {
						$details[] = [
							'ORDERCODE' => "O" . $order->order_id,
                            'TYPE' => $order_total->code,
							'LINENO' => $lineno,
							'SHOPCODE' => '',
							'PRODUCTCODE' => '',
							'PUCODE' => '',
							'QUANTITY' => 0,
							'PRICE' => 0,
							'AMOUNT' => $order_total->value,
							'PAYMENT'=>$order_total->value,
							'DESCRIPTION' => $order_total->title,
							'PROMOTIONDETAILCODE' => '',
							'SCANS' => [],
							'COUPONCODE' => $order_total->coupon ? $order_total->coupon->code : '',
						];
						$lineno++;
					}
				}
				$orderdata = array_merge($orderdata, ['DETAILS' => $details]);
				$input_data[] = $orderdata;
			}
			echo "data-load-time:" . date("Y-m-d H:i:s", time()) . "\n";
			sleep(30);
			if ($input_data) {
				$client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], ['soap_version' => SOAP_1_1, 'exceptions' => false]);
				foreach ($input_data as $value) {
					$data = $this->CreatRequestParams('createOrder', [$value]);
					$content = $client->getInterfaceForJson($data);
					$result = $this->getResult($content);
					echo $value['CODE'] . "---data-upload-time:" . date("Y-m-d H:i:s", time()) . "\n";
					if (isset($result['status']) && $result['status'] == 'OK') {
                        ;
						$model = Order::findOne(['order_id' => substr($value['CODE'],1)]);
						if ($model) {
						    if(strtolower($model->order_type_code)=="recharge"){
                                $model->order_status_id = 11;
                            }
							$model->sent_to_erp = "Y";
							$model->date_modified = date("Y-m-d H:i:s", time());
							$model->sent_time = date("Y-m-d H:i:s", time());
							$model->save();
						}
						if ($model->orderProducts) {
							if($model->order_type_code=="GroundPush"){
//								if($ground_model=GroundPushPointToCustomer::findOne(['order_id'=>$model->order_id])){
//									foreach ($model->orderProducts as $order_product) {
//										$fn = function ($ground_model,$order_product) use (&$fn){
//											$ground_stock=GroundPushStock::findOne(['ground_push_point_id'=>$ground_model->point_id,'product_code'=>$order_product->product_code]);
//											if($ground_stock){
//												try{
//													//$ground_stock->tmp_qty=$ground_stock->tmp_qty-$order_product->quantity;
//													//$ground_stock->qty=max(0,$ground_stock->qty-$order_product->quantity);
//													$ground_stock->last_time=time();
//													$ground_stock->save(false);
//												}catch (StaleObjectException $e){
//													$fn($ground_model,$order_product);
//												}
//											}
//										};
//										$fn($ground_model,$order_product);
//									}
//								}
							}else{
								foreach ($model->orderProducts as $order_product) {
									if ($m = WarehouseStock::findOne(['product_code' => $order_product->product_code])) {
										$m->tmp_qty = max(0, $m->tmp_qty - $order_product->quantity);
										$m->quantity = max(0, $m->quantity - $order_product->quantity);
										$m->save();
									}
									if ($order_product->gift) {
										foreach ($order_product->gift as $order_product_gift) {
											if ($m = WarehouseStock::findOne(['product_code' => $order_product_gift->product_code])) {
												$m->tmp_qty = max(0, $m->tmp_qty - $order_product_gift->quantity);
												$m->quantity = max(0, $m->quantity - $order_product_gift->quantity);
												$m->save();
											}
										}
									}
								}
							}
						}
						if ($model->orderGifts) {
							foreach ($model->orderGifts as $order_product) {
								if ($m = WarehouseStock::findOne(['product_code' => $order_product->product_code])) {
									$m->tmp_qty = max(0, $m->tmp_qty - $order_product->quantity);
									$m->quantity = max(0, $m->quantity - $order_product->quantity);
									$m->save();
								}
							}
						}
					} else {
						print_r($result);
					}
				}
			}
			echo "complate:" . date("Y-m-d H:i:s", time()) . "-----" . count($input_data);
		}

		//取消订单数据
		public	function actionCancel()
		{
			$hour = 0.5;//自动取消时间跨度
			$order_datas = Order::find()->Where(['and', 'order_status_id=1', 'UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date_added) >=' . $hour * 3600])
				->orderBy(['order_id' => 'SORT_ASC'])->asArray()->all();
			if ($order_datas) {
				foreach ($order_datas as $order_data) {
					//更新订单状态并记录日志
					if ($model = Order::findOne(['order_id' => $order_data['order_id']])) {
						$model->order_status_id = 7;
						$model->date_modified = date("Y-m-d H:i:s", time());
						$model->update();
						if ($model->orderProducts) {
							if($model->order_type_code=="GroundPush"){
								if($ground_model=GroundPushPointToCustomer::findOne(['order_id'=>$model->order_id])){
									foreach ($model->orderProducts as $order_product) {
										$fn = function ($ground_model,$order_product) use (&$fn){
											$ground_stock=GroundPushStock::findOne(['ground_push_point_id'=>$ground_model->point_id,'product_code'=>$order_product->product_code]);
											if($ground_stock){
												try{
													$ground_stock->tmp_qty=$ground_stock->tmp_qty-$order_product->quantity;
													$ground_stock->last_time=date("Y-m-d H:i:s");
													$ground_stock->save(false);
												}catch (StaleObjectException $e){
													$fn($ground_model,$order_product);
												}
											}
										};
										$fn($ground_model,$order_product);
									}
								}
							}else{
								foreach ($model->orderProducts as $order_product) {
									if ($m = WarehouseStock::findOne(['product_code' => $order_product->product_code])) {
										$m->tmp_qty = max(0, $m->tmp_qty - $order_product->quantity);
										$m->save();
									}
									if ($order_product->gift) {
										foreach ($order_product->gift as $order_product_gift) {
											if ($m = WarehouseStock::findOne(['product_code' => $order_product_gift->product_code])) {
												$m->tmp_qty = max(0, $m->tmp_qty - $order_product_gift->quantity);
												$m->save();
											}
										}
									}
								}
							}
						}
						if ($model->orderGifts) {
							foreach ($model->orderGifts as $order_product) {
								if ($m = WarehouseStock::findOne(['product_code' => $order_product->product_code])) {
									$m->tmp_qty = max(0, $m->tmp_qty - $order_product->quantity);
									$m->save();
								}
							}
						}
						$order_history = new OrderHistory();
						$order_history->order_status_id = 7;
						$order_history->order_id = $order_data['order_id'];
						$order_history->notify = 0;
						$order_history->comment = "订单超时，系统取消订单";
						$order_history->date_added = date("Y-m-d H:i:s", time());
						$order_history->save();
						if ($open_id = $model->customer->getWxOpenId()) {
							$message = "";
							$notice = new WxNotice();
							$notice->order($open_id, "http://m.mrhuigou.com/order/index", ['title' => '尊敬的家润会员', 'order_no' => $model->order_no, 'status' => '订单超时，系统取消！', 'remark' => $message]);
						}
//						if($model->use_points){
//                            $point_customer_flows = PointCustomerFlow::find()->where(['type'=>'order','type_id'=>$model->order_id])->all();
//                            if($point_customer_flows){
//                                foreach ($point_customer_flows as $point_customer_flow){
//                                    $point_model = $point_customer_flow->pointCustomer->point;
//                                    $data['telephone'] = $model->telephone;
//                                    $data['changeType'] = 1; //1增加 2扣除
//                                    $data['description'] = '订单取消';
//                                    $data['orderId'] = $model->order_id;
//                                    $data['count'] = 1;
//                                    $data['status'] = 0;
//                                    $data['creditValue'] = $point_customer_flow->amount;
//                                    $data['changeDate'] = date('Y-m-d H:i:s');
//                                    $data['changeResource'] = 6;
//                                    $data['point_customer_flow_id'] = $point_customer_flow->point_customer_flow_id;
//                                    $point_model->notice($data);
//					}
//                            }
//                        }
					}
					//返还优惠券
					$order_totals = OrderTotal::find()->where(['code' => 'coupon', 'order_id' => $order_data['order_id']])->all();
					if ($order_totals) {
						foreach ($order_totals as $order_total) {
							if ($customer_coupon = CustomerCoupon::findOne(['customer_coupon_id' => $order_total->customer_code_id, 'is_use' => 1])) {
								$customer_coupon->is_use = 0;
								$customer_coupon->date_used = '';
								$customer_coupon->save();
							}
						}
						CouponHistory::deleteAll(['order_id' => $order_data['order_id']]);
					}
					//更新促销状态
					PromotionHistory::updateAll(['status' => 0], ['order_id' => $order_data['order_id']]);
					OrderBlack::deleteAll(['order_id' => $order_data['order_id']]);
				}
			}
			echo "run time" . date("Y-m-d H:i:s", time());
		}

		//同步周期订单
		public function actionCycel()
		{
			$order_Cycles = OrderCycle::find()->where(['order_status_id' => 2, 'sent_to_erp' => 0, 'status' => 1])->limit(10)->asArray()->all();
			$input_data = [];
			if ($order_Cycles) {
				foreach ($order_Cycles as $order_Cycle) {
					$OrderData = Order::findOne(['order_id' => $order_Cycle['order_id']]);
					$platform = Platform::findOne(['platform_id' => $OrderData->platform_id]);
					$store = Store::findOne(['store_id' => $OrderData->store_id]);
					//订单基础数据
					$order_base_data = [
						'CODE' => "O" . $OrderData->order_id . "-" . $order_Cycle['order_cycle_id'],
						'PARENTCODE' => "",
						'ORDERDATE' => $OrderData->date_modified,
						'SOURCE' => $OrderData->user_agent == 'APP' ? 'APP' : "WEB",
						'PLATFORMCODE' => $platform ? $platform->platform_code : '',
						'TYPE' => strtoupper($OrderData->order_type_code),
						'RETURNTYPE' => '',
						'MEMBERCODE' => $OrderData->customer_id,
						'DESCRIPTION' => $OrderData->comment,
						'RELATEDBILL1' => '',
						'RELATEDBILL2' => '',
						'SHOPCODE' => $store ? $store->store_code : '',
						'SCANS' => []
					];
					//订单配送信息
					$order_shipping = OrderShipping::findOne(['order_id' => $OrderData->order_id]);
					if ($order_shipping) {
						$order_shipping_data = [
							'CONTACTNAME' => $order_shipping->shipping_firstname,
							'TELEPHONE' => $order_shipping->shipping_telephone,
							'MOBILE' => $order_shipping->shipping_telephone,
							'CITY' => $order_shipping->shipping_city,
							'ADDRESS' => $order_shipping->shipping_address_1,
							'DELIVERY_TYPE' => strtoupper($order_shipping->delivery_code),
							'DELIVERY_VALUE' => $order_Cycle['shipping_time'],
							'BEDELIVERY' => $OrderData->delivery_type ? true : false,
						];
					} else {
						$order_shipping_data = [
							'CONTACTNAME' => "",
							'TELEPHONE' => "",
							'MOBILE' => "",
							'CITY' => "",
							'ADDRESS' => "",
							'DELIVERY_TYPE' => "",
							'DELIVERY_VALUE' => "",
							'BEDELIVERY' => $OrderData->delivery_type ? true : false,
						];
					}
					//订单支付数据
					$order_payment = OrderPayment::findOne(['order_id' => $OrderData->order_id]);
					if ($order_payment) {
						$order_payment_data = [
							'PAYTYPECODE' => strtoupper($order_payment->payment_code),
							'PAYTYPE' => $order_payment->payment_method,
							'PAYMENT' => $order_payment->total,
							'SERIAL_NUMBER' => $order_payment->payment_deal_no,
						];
					} else {
						$order_payment_data = [
							'PAYTYPECODE' => "",
							'PAYTYPE' => "",
							'PAYMENT' => "",
							'SERIAL_NUMBER' => "",
						];
					}
					//订单发票信息
					$order_invoice = ['0' => '不需要发票', '1' => '个人', '2' => '企业'];
					$invoice_type = array_search($OrderData->invoice_temp, $order_invoice);
					$order_invoice_data = [
						'INVOICE_TYPE' => $invoice_type,
						'INVOICE_NAME' => $OrderData->invoice_title,
						'INVOICE_COMPANY' => '',
						'INVOICE_TAX_NUMBER' => '',
						'INVOICE_ADD_TEL' => '',
						'INVOICE_BANK' => '',
					];
					$orderdata = array_merge($order_base_data, $order_shipping_data, $order_payment_data, $order_invoice_data);
					$order_totals = 0;
					$details = [];
					$lineno = 1;
					//订单商品信息
					$order_product_data = OrderCycleDetail::find()->where(['order_cycle_id' => $order_Cycle['order_cycle_id']])->asArray()->all();
					if ($order_product_data) {
						foreach ($order_product_data as $OrderProduct) {
							if ($order_Cycle['cycle_store_id']) {
								$price = bcdiv($OrderProduct['price'], $OrderProduct['quantity'], 4);
								$total = $OrderProduct['price'];
							} else {
								$price = $OrderProduct['price'];
								$total = bcmul($OrderProduct['price'], $OrderProduct['quantity'], 4);
							}
							$order_totals = bcadd($order_totals, $total, 4);
							$product_data = Product::findOne(['product_id' => $OrderProduct['product_id']]);
							$details[] = [
								'ORDERCODE' => "O" . $OrderData->order_id . "-" . $order_Cycle['order_cycle_id'],
								'LINENO' => $lineno,
								'SHOPCODE' => $product_data->store_code,
								'PRODUCTCODE' => $product_data->product_base_code,
								'PUCODE' => $OrderProduct['product_code'],
								'QUANTITY' => $OrderProduct['quantity'],
								'PRICE' => $price,
								'AMOUNT' => $total,
								'DESCRIPTION' => $OrderProduct['comment'],
								'PROMOTIONDETAILCODE' => "",
								'SCANS' => []
							];
							$lineno++;
						}
					}
					$orderdata = array_merge($orderdata, ["PAYMENT" => $order_totals]);
					//订单总计信息
					$order_total_data = OrderTotal::find()->where(['order_id' => $OrderData->order_id])->asArray()->all();
					foreach ($order_total_data as $order_total) {
						if (isset($order_total['code']) && !in_array($order_total['code'], ['shipping', 'coupon'])) {
							$details[] = [
								'ORDERCODE' => "O" . $OrderData->order_id . "-" . $order_Cycle['order_cycle_id'],
								'LINENO' => $lineno,
								'SHOPCODE' => '',
								'PRODUCTCODE' => '',
								'PUCODE' => '',
								'QUANTITY' => 0,
								'PRICE' => 0,
								'AMOUNT' => $order_totals,
								'DESCRIPTION' => $order_total['title'],
								'PROMOTIONDETAILCODE' => '',
								'SCANS' => []
							];
							$lineno++;
						}
					}
					$orderdata = array_merge($orderdata, ['DETAILS' => $details]);
					$input_data[] = $orderdata;
				}
			}
			if ($input_data) {
				$client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], ['soap_version' => SOAP_1_1, 'exceptions' => false]);
				foreach ($input_data as $value) {
					$data = $this->CreatRequestParams('createOrder', [$value]);
					$content = $client->getInterfaceForJson($data);
					$result = $this->getResult($content);
					if (isset($result['status']) && $result['status'] == 'OK') {
						$id = trim($value['CODE'], 'O');
						if (strpos($id, "-")) {
							list($order_id, $order_cycle_id) = explode("-", $id);
							$model = OrderCycle::findOne(['order_cycle_id' => $order_cycle_id, 'order_id' => $order_id]);
							if ($model) {
								$model->sent_to_erp = 1;
								$model->sent_time = date("Y-m-d H:i:s", time());
								$model->save();
							}
						}
					}
				}
			}
			echo "run time" . date("Y-m-d H:i:s", time());
		}
		//同步后台退货订单
		public function actionReturn()
		{
			$return_order_datas = ReturnBase::find()->Where(['return_status_id' => 7, "send_status" => 0])->orderBy(['date_added' => 'SORT_ASC'])->limit(5)->asArray()->all();
			$input_data = [];
			if ($return_order_datas) {
				foreach ($return_order_datas as $return_order) {
					$OrderData = Order::findOne(['order_id' => $return_order]);
					$platform = Platform::findOne(['platform_id' => $OrderData->platform_id]);
					$store = Store::findOne(['store_id' => $OrderData->store_id]);
					//订单基础数据
					$order_base_data = [
						'CODE' => $return_order['return_code'],
						'PARENTCODE' => $OrderData['parent_id'] ? "O" . $OrderData['parent_id'] : '',
						'ORDERDATE' => $OrderData['date_modified'],
						'SOURCE' => $OrderData['user_agent'] == 'APP' ? 'APP' : "WEB",
						'PLATFORMCODE' => $platform ? $platform->platform_code : '',
						'TYPE' => 'RETURN',
						'RETURNTYPE' => $return_order['is_all_return'] ? "ALL" : "PAPT",
						'MEMBERCODE' => $return_order['customer_id'],
						'DESCRIPTION' => $return_order['comment'],
						'RELATEDBILL1' => $OrderData->order_id,
						'RELATEDBILL2' => '',
						'SHOPCODE' => $store ? $store->store_code : '',
						'SCANS' => []
					];
					//订单配送信息
					$order_shipping = OrderShipping::findOne(['order_id' => $return_order['order_id']]);
					if ($order_shipping) {
						$order_shipping_data = [
							'CONTACTNAME' => $order_shipping->shipping_firstname,
							'TELEPHONE' => $order_shipping->shipping_telephone,
							'MOBILE' => $order_shipping->shipping_telephone,
							'CITY' => $order_shipping->shipping_city,
							'ADDRESS' => $order_shipping->shipping_address_1,
							'DELIVERY_TYPE' => strtoupper($order_shipping->delivery_code),
							'DELIVERY_VALUE' => $order_shipping->delivery,
							'BEDELIVERY' => $OrderData->delivery_type ? true : false,
						];
					} else {
						$order_shipping_data = [
							'CONTACTNAME' => $return_order['firstname'],
							'TELEPHONE' => $return_order['telephone'],
							'MOBILE' => $return_order['telephone'],
							'CITY' => "",
							'ADDRESS' => "",
							'DELIVERY_TYPE' => "",
							'DELIVERY_VALUE' => "",
							'BEDELIVERY' => $OrderData->delivery_type ? true : false,
						];
					}
					//订单支付数据
					$order_payment = OrderPayment::findOne(['order_id' => $return_order['order_id']]);
					if ($order_payment) {
						$order_payment_data = [
							'PAYTYPECODE' => strtoupper($order_payment->payment_code),
							'PAYTYPE' => $order_payment->payment_method,
							'PAYMENT' => $return_order['total'],
							'SERIAL_NUMBER' => $order_payment->payment_deal_no,
						];
					} else {
						$order_payment_data = [
							'PAYTYPECODE' => "",
							'PAYTYPE' => "",
							'PAYMENT' => $return_order['total'],
							'SERIAL_NUMBER' => "",
						];
					}
					//订单发票信息
					$order_invoice = ['0' => '不需要发票', '1' => '个人', '2' => '企业'];
					$invoice_type = array_search($OrderData->invoice_temp, $order_invoice);
					$order_invoice_data = [
						'INVOICE_TYPE' => $invoice_type,
						'INVOICE_NAME' => $OrderData->invoice_title,
						'INVOICE_COMPANY' => '',
						'INVOICE_TAX_NUMBER' => '',
						'INVOICE_ADD_TEL' => '',
						'INVOICE_BANK' => '',
					];
					$orderdata = array_merge($order_base_data, $order_shipping_data, $order_payment_data, $order_invoice_data);
					////订单商品信息
					$details = [];
					$lineno = 1;
					$return_products = ReturnProduct::find()->where(['return_id' => $return_order['return_id']])->asArray()->all();
					if ($return_products) {
						foreach ($return_products as $return_product) {
							$details[] = [
								'ORDERCODE' => $return_product['return_code'],
								'LINENO' => $lineno,
								'SHOPCODE' => $return_product['store_code'],
								'PRODUCTCODE' => $return_product['product_base_code'],
								'PUCODE' => $return_product['product_code'],
								'QUANTITY' => $return_product['quantity'],
								'PRICE' => bcdiv($return_product['total'], $return_product['quantity'], 4),
								'AMOUNT' => $return_product['total'],
								'DESCRIPTION' => $return_product['comment'],
								'PROMOTIONDETAILCODE' => "",
								'SCANS' => []
							];
							$lineno++;
						}
					}
					$orderdata = array_merge($orderdata, ['DETAILS' => $details]);
					$input_data[] = $orderdata;
				}
			}
			// print_r($input_data);
			if ($input_data) {
				$client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], ['soap_version' => SOAP_1_1, 'exceptions' => false]);
				foreach ($input_data as $value) {
					$data = $this->CreatRequestParams('createOrder', [$value]);
					$content = $client->getInterfaceForJson($data);
					$result = $this->getResult($content);
					//   print_r($result);
					if (isset($result['status']) && $result['status'] == 'OK') {
						$model = ReturnBase::findOne(['return_code' => $value['CODE']]);
						if ($model) {
							$model->return_status_id = 1;
							$model->send_status = 1;
							$model->date_modified = date("Y-m-d H:i:s", time());
							$model->save();
							$model = new ReturnHistory();
							$model->return_status_id = 1;
							$model->return_id = trim($value['CODE'], "RO");
							$model->notify = 0;
							$model->comment = "订单已经同步";
							$model->date_added = date("Y-m-d H:i:s", time());
							$model->save();
						}

					}
				}
			}
			echo "run time" . date("Y-m-d H:i:s", time());
		}

		public	function actionDeliveryComment()
		{
			$model = OrderDeliveryComment::find()->where(['send_status' => 0])->orderBy('id')->limit(100)->all();
			$input_data = [];
			if ($model) {
				foreach ($model as $value) {
					$input_data[] = [
						'CODE' => $value->id,
						'ORDERCODE' => "O" . $value->order_id,
						'SCORE' => $value->score?$value->score:1,
						'TAGS' => $value->tags?$value->tags:"",
						'COMMENT' => $value->comment?$value->comment:'',
						'UPDATETIME' => date('Y-m-d H:i:s', $value->created_at)
					];
				}
			}
			if ($input_data) {
				$client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], ['soap_version' => SOAP_1_1, 'exceptions' => false]);
				try {
					foreach ($input_data as $value) {
						$data = $this->CreatRequestParams('evaluateOrder', [$value]);
						$content = $client->getInterfaceForJson($data);
						if (is_soap_fault($content)) {
							throw new \Exception();
						}
						$result = $this->getResult($content);
						if (isset($result['status']) && $result['status'] == 'OK') {
							if ($sub_model = OrderDeliveryComment::findOne($value['CODE'])) {
								$sub_model->send_status = 1;
								$sub_model->save();
							}
						}
					}
				} catch (\Exception $e) {
					echo $e->getMessage();
				}
			}
			echo "run time" . date("Y-m-d H:i:s", time());
		}

		//同步RF订单信息
		public	function actionRforder()
		{
			$client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], ['soap_version' => SOAP_1_1, 'exceptions' => false]);
			try {
				$data = $this->CreatRequestParams('getRFReturnOrderForJson');
				$content = $client->getInterfaceForJson($data);
				if (is_soap_fault($content)) {
					throw new \Exception();
				}
				$result = Json::decode($content);
				print_r($result);
				exit;
				if (isset($result['data']) && $result['data']) {
					print_r($result['data']);
				}
			} catch (\Exception $e) {
				echo $e->getMessage();
			}
			echo "run time" . date("Y-m-d H:i:s", time());
		}
    public function actionAutoRefound(){
        //地推订单自动退货
        $orders = Order::find()->where(['order_status_id'=>2,'order_type_code'=>'GroundPush'])->all();
        if($orders){
            foreach ($orders as $order){
                $return_base = ReturnBase::findOne(['order_id'=>$order->order_id]);
                if(!$return_base){
                    $model = new ReturnAllForm(['order'=>$order]);
                    $model->setReturnStatus(1);
                    $model->return_model = 'RETURN_PAY';
                    $model->comment = '<地推订单自动退货>';
                    $model->username = $order->firstname ? $order->firstname : '匿名' ;
                    $model->telephone = $order->telephone;
                    $model->paymethod = 1;
                    $return_base = $model->submit();
                    //同步后台
                    if($this->sendReturnGroundPush($return_base)){
                        $return_base->send_status = 1;
                        $return_base->save();
                    }else{
                        $return_base->send_status = 0;
                        $return_base->save();
                    }
                    //地推订单库存操作
                    $this->returnGroundPushStock($order);
                }else{
                    if($return_base->send_status == 0){
                        if($this->sendReturnGroundPush($return_base)){
                            $return_base->send_status = 1;
                            $return_base->save();
                        }else{
                            $return_base->send_status = 0;
                            $return_base->save();
                        }
                    }
                }
            }
        }

    }
    private function returnGroundPushStock($order){
        if($order){
            $ground_push_point_to_customer = GroundPushPointToCustomer::findOne(['customer_id'=>$order->customer_id,'order_id'=>$order->order_id]);
            if($ground_push_point_to_customer){
                $point_id = $ground_push_point_to_customer->point_id;
                if($order->orderProducts){
                    foreach ($order->orderProducts as $order_product){
                        // $ground_push_stock = GroundPushStock::findOne(['product_code'=>$orderProduct->product_code,'ground_push_point_id'=>$point_id]);

                        $fn = function ($point_id,$order_product) use (&$fn){
                            $ground_stock=GroundPushStock::findOne(['ground_push_point_id'=>$point_id,'product_code'=>$order_product->product_code]);
                            if($ground_stock){
                                try{
                                    $ground_stock->tmp_qty = $ground_stock->tmp_qty-$order_product->quantity;
                                    $ground_stock->last_time=date("Y-m-d H:i:s");
                                    $ground_stock->save(false);
                                }catch (StaleObjectException $e){
                                    $fn($point_id,$order_product);
                                }
                            }
                        };
                        $fn($point_id,$order_product);
                    }
                }
            }
        }
    }
    private function sendReturnGroundPush($return_model){
        // $return_id = $return_model->return_id;
        $return_info = $return_model;
        if($return_info){
            $order_info= Order::findOne($return_info->order_id);
            $orderAddress = OrderShipping::findOne(['order_id'=>$return_info->order_id]);
            $returnProducts = $return_info->returnProduct;
            $return_total=$return_info->totals;
            $return_product = [];
            $line_no=1;
            if($returnProducts){
                foreach($returnProducts as $key=>$returnproduct){

                    $return_product[]=[
                        'ORDERCODE'=>$return_info->return_code,
                        'TYPE' => 'product',
                        'LINENO'=>$line_no,
                        'SHOPCODE'=>$return_info->order->store->store_code,
                        'PRODUCTCODE'=>$returnproduct->product_base_code,
                        'PUCODE'=>$returnproduct->product_code,
                        'QUANTITY'=>$returnproduct->quantity,
                        'PRICE'=>bcdiv($returnproduct->total,$returnproduct->quantity,2),
                        'PAYMENT'=>$returnproduct->total,
                        'PROMOTIONDETAILCODE'=>'',
                        'AMOUNT'=>$returnproduct->total,
                        'DESCRIPTION'=>$returnproduct->comment?$returnproduct->comment:'',
                        'SCANS'=>[]
                    ];
                    $line_no++;
                }
            }
            if($return_total){
                foreach($return_total as $value){
                    $return_product[]=[
                        'ORDERCODE'=>$return_info->return_code,
                        'TYPE' => $value->code,
                        'LINENO'=>$line_no,
                        'SHOPCODE'=>$return_info->order->store->store_code,
                        'PRODUCTCODE'=>'',
                        'PUCODE'=>'',
                        'QUANTITY'=>'',
                        'PRICE'=>'',
                        'PAYMENT'=>'',
                        'PROMOTIONDETAILCODE'=>'',
                        'AMOUNT'=>$value->value,
                        'DESCRIPTION'=>$value->title,
                        'SCANS'=>[]
                    ];
                    $line_no++;
                }
            }
            $order_invoice=array('0'=>'不需要发票','1'=>'个人','2'=>'企业');

            $return_data=array(
                'CODE'=>$return_info->return_code,
                'PARENTCODE'=>'',
                'ORDERDATE'=>$return_info->date_added,
                'PLATFORMCODE'=>'PT0001',
                'STATUS'=>'ACTIVE',
                'BEDELIVERY'=>true,
                'SOURCE'=>'WEB',
                'TYPE'=>'RETURN',
                'RETURNTYPE'=> 'PART',
                'RETURN_DEAL_METHOD'=>strtoupper($return_info->return_method),
                'PAYTYPE'=>$order_info->payment_method,
                'PAYMENT'=>$return_info->total,
                'MEMBERCODE'=> $order_info->customer_id,
                //'SHIPPING'=> $shipping,
                'DELIVERY_STATION_CODE'=>'',
                'CONTACTNAME'=>$return_info->firstname, //$order_info['shipping_lastname'].$order_info['shipping_firstname'],
                'TELEPHONE'=>$return_info->telephone, //$order_info['shipping_telephone'],
                'MOBILE'=>$return_info->telephone, //$order_info['shipping_telephone'],
                'CITY'=> (isset($orderAddress->shipping_city)?$orderAddress->shipping_city:'').(isset($orderAddress->shipping_district)?$orderAddress->shipping_district:''), //$order_info['shipping_city'],
                'ADDRESS'=> (isset($orderAddress->shipping_district)?$orderAddress->shipping_district:'').(isset($orderAddress->shipping_address_1)?$orderAddress->shipping_address_1:''), //$order_info['shipping_address_1'],
                'DESCRIPTION'=>$return_info->comment?$return_info->comment:'',
                'RELATEDBILL1'=>$return_info->order_code,
                'RELATEDBILL2'=>'',
                'PAYTYPECODE'=> '',
                'DELIVERY_TYPE'=> '',
                'DELIVERY_VALUE'=> '',
                'SERIAL_NUMBER'=> '',
                'INVOICE_TYPE'=>array_search($order_info->invoice_temp,$order_invoice),
                'INVOICE_NAME'=>$order_info->invoice_title,
                'INVOICE_COMPANY'=>$order_info->invoice_title,
                'INVOICE_TAX_NUMBER'=>'', //$order_info->invoice_tax_number'],
                'INVOICE_ADD_TEL'=>'', //$order_info->invoice_add_tel'],
                'INVOICE_BANK'=>'', //$order_info->invoice_bank'],
                'DETAILS'=>$return_product,
                'SCANS'=>array(),
                'USEPOINTS'=>$order_info->use_points ? true : false,
            );
            if($return_data){
                $erp_wsdl = \Yii::$app->params['ERP_SOAP_URL'];
                $client = new \SoapClient($erp_wsdl, array('soap_version' => SOAP_1_1, 'exceptions' => false));
                $data=$this->CreatRequestParams('createOrder',array($return_data));
                $content = $client->getInterfaceForJson($data);
                $result=$this->getResult($content);
                if($result['status']=='OK'){
                    $return_info->send_status = 1;
                    $return_info->save();
                    return true;
                }
            }
        }
        return false;
    }
    public function actionAutoReceive(){
        //学生地推点自动收货
        $orders = Order::find()->where(['order_status_id'=>2,'order_type_code'=>'GroundPush'])->all();
        if($orders) {
            $count = 1;
            foreach ($orders as $key => $order) {
                if($order){
                    $point_to_customer = GroundPushPointToCustomer::findOne(['order_id' => $order->order_id]);
                    if ($point_to_customer->point && $point_to_customer->point->type == 'CROSS') {
                        $order->order_status_id = 10;
                        $order->save();
                        echo $count . "====>" . $order->order_id;
                        echo "<br>";
                        $count++;
                    }
                }

            }
        }
    }

	}
