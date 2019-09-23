<?php
namespace frontend\controllers;
use api\models\V1\Coupon;
use api\models\V1\CouponHistory;
use api\models\V1\CustomerCoupon;
use api\models\V1\Order;
use api\models\V1\OrderBlack;
use api\models\V1\OrderMerge;
use api\models\V1\PlatformStation;
use api\models\V1\PromotionHistory;
use api\models\V1\PromotionOrder;
use common\behavior\NoCsrf;
use frontend\models\AddressForm;
use frontend\models\CheckoutForm;
use frontend\widgets\Checkout\StorePromotion;
use Yii;
use api\models\V1\Address;
use api\models\V1\Store;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class CheckoutController extends \yii\web\Controller {
	public $order_product_rate = [];

	public function behaviors()
	{
		return [
			'csrf' => [
				'class' => NoCsrf::className(),
				'controller' => $this,
				'actions' => [
					'complate'
				]
			]
		];
	}

	public function actionIndex()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
		}
		if (!Yii::$app->user->identity->telephone_validate) {
			return $this->redirect(['/security/security-validate-telephone', 'redirect' => '/checkout/index']);
		}
		if (!$cart = \Yii::$app->session->get('confirm_cart')) {
			return $this->redirect('/cart/index');
		}
		if (Yii::$app->request->isPost && ($checkForm = Yii::$app->request->post('CheckoutForm'))) {
			if ($this->getOrderBlackScore($checkForm['address_id']) > 4) {
				Yii::$app->session->remove('FirstBuy');
			}
		}
		//商品进行店铺拆分
		$cart_datas = [];
		foreach ($cart as $value) {
			if (!$value->hasStock()) {
				return $this->redirect(['/cart/index']);
			}
			$cart_datas[$value->product->store_id][] = $value;
		}
		$comfirm_orders = [];
		if ($cart_datas) {
			//分别统计每家店铺的数据
			foreach ($cart_datas as $key => $cart_data) {
				$comfirm_orders[$key]['base'] = Store::findOne(['store_id' => $key]);
				$comfirm_orders[$key]['products'] = $cart_data;
				$comfirm_orders[$key]['total'] = 0;
				$comfirm_orders[$key]['totals'] = [];
				$comfirm_orders[$key]['rate']=[];
				$comfirm_orders[$key]['promotion'] = [];
				$comfirm_orders[$key]['coupon_gift'] = [];
				//计算商品金额
				$this->getSubTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data);
				//计算运费金额
				$shipping_cost = 0;
				if ($deliveries = Yii::$app->request->post('CheckoutForm')) {
					$delivery = isset($deliveries['delivery'][$key]['type']) ? $deliveries['delivery'][$key]['type'] : 'delivery';
					if ($delivery == 'delivery') {
						$delivery_station_id = 0;
					} else {
						$delivery_station_id = isset($deliveries['delivery'][$key]['station_id']) ? $deliveries['delivery'][$key]['station_id'] : 0;
					}
				} else {
					$delivery_station_id = 0;
				}
				$this->getShippingTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data, $key, $shipping_cost, $delivery_station_id);
				if ($coupons = Yii::$app->request->post('CheckoutForm')) {
					$customer_coupon_id = isset($coupons['coupon'][$key]) ? $coupons['coupon'][$key] : 0;
				} else {
					$customer_coupon_id = 0;
				}
				//计算全局优惠券金额
				$this->getGlobalCouponTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data, $key, $shipping_cost, $comfirm_orders[$key]['coupon_gift'],$comfirm_orders[$key]['rate']);
				//计算优惠金额
				$this->getCouponTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data, explode(',',$customer_coupon_id), $shipping_cost, $comfirm_orders[$key]['coupon_gift'],$comfirm_orders[$key]['rate']);
				//订单满减金额
				$this->getOrderTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data, $key, $comfirm_orders[$key]['promotion']);
				//应付订单金额
				$this->getTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total']);
			}
		}
		Yii::$app->session->set('comfirm_orders', $comfirm_orders);
		//计算总计金额
		$merge_order_total = 0;
		if ($comfirm_orders) {
			foreach ($comfirm_orders as $value) {
				$merge_order_total = bcadd($merge_order_total, $value['total'], 2);
			}
		}
		$model = new CheckoutForm($comfirm_orders, $this->order_product_rate);
		if ($model->load(Yii::$app->request->post()) && $trade_no = $model->submit()) {
			Yii::$app->session->remove('comfirm_orders');
			Yii::$app->session->remove('confirm_cart');
			if (!Yii::$app->cart->getIsEmpty()) {
				foreach ($cart as $key => $id) {
					if (Yii::$app->cart->hasPosition($key)) {
						if (\Yii::$app->session->get('FirstBuy') && \Yii::$app->session->get('FirstBuy') == $key) {
							\Yii::$app->session->remove('FirstBuy');
						}
						Yii::$app->cart->removeById($key);
					}
				}
			}
			return $this->redirect(['/payment/index', 'trade_no' => $trade_no]);
		}
		return $this->render('index', [
			'cart' => $comfirm_orders,
			'pay_total' => number_format($merge_order_total, 2),
			'model' => $model
		]);
	}

	public function actionComplate()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		if (!$order_merge = OrderMerge::findOne(['merge_code' => \Yii::$app->request->get('trade_no'), 'customer_id' => Yii::$app->user->getId()])) {
			return $this->redirect('/order/index');
		}
		$order_ids = explode(',', $order_merge->order_ids);
		$orders = [];
		if (!empty($order_ids)) {
			foreach ($order_ids as $order_id) {
				$orders[] = Order::findOne(['order_id' => $order_id]);
			}
		}
		return $this->render("complate", ['order_merge' => $order_merge, 'orders' => $orders]);
	}

	public function actionDeliveryAjax()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		$station_id = Yii::$app->request->get('delivery_station_id');
		$store_id = Yii::$app->request->get('store_id');
		if ($model = Store::findOne(['store_id' => $store_id])) {
			if ($model->station) {
				$data = [];
				foreach ($model->station as $key => $value) {
					if ((!$station_id && ($key == $station_id)) || ($station_id == $value->id)) {
						$station_id = $value->id;
						$data['default'] = [
							'lat' => $value->latitude,
							'lng' => $value->longitude,
						];
					}
					$data['data'][] = [
						'lat' => $value->latitude,
						'lng' => $value->longitude,
					];
				}
				if (($order_delivery_username = Yii::$app->session->get('order_delivery_username')) && isset($order_delivery_username[md5($store_id)])) {
					$username = $order_delivery_username[md5($store_id)];
				} else {
					$username = Yii::$app->user->identity->nickname;
				}
				if (($order_delivery_telephone = Yii::$app->session->get('order_delivery_telephone')) && isset($order_delivery_telephone[md5($store_id)])) {
					$telephone = $order_delivery_telephone[md5($store_id)];
				} else {
					$telephone = Yii::$app->user->identity->telephone;
				}
				return $this->render('delivery', ['model' => $model, 'data' => Json::encode($data), 'station_id' => $station_id, 'username' => $username, 'telephone' => $telephone]);
			}
		}
	}

	public function actionDeliverySelectTime()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		if (($store_id = Yii::$app->request->get('store_id')) && ($date = Yii::$app->request->get('date'))) {
			return $this->render('delivery-select-time', ['store_id' => $store_id, 'date' => $date]);
		}
	}

	public function actionDeliveryStationAjax()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		if ($type = Yii::$app->request->post('delivery_type')) {
			if (($type == 'self-delivery') && ($model = PlatformStation::findOne(['id' => Yii::$app->request->post('delivery_station_id')]))) {
				return $this->renderPartial('delivery-station', ['model' => $model]);
			}
		}
	}

	public function getSubTotal(&$total_data, &$total, $cart)
	{
		$sub_total = 0;
		if ($cart) {
			foreach ($cart as $value) {
				$sub_total = bcadd($sub_total, $value->getCost(), 2);
			}
		}
		$total_data[] = [
			'code' => 'sub_total',
			'title' => "商品总额",
			'value' => $sub_total,
			'sort_order' => 1
		];
		$total = bcadd($total, $sub_total, 2);
	}

	public function getShippingTotal(&$total_data, &$total, $cart, $store_id = 0, &$shipping_cost, $delivery_station_id = 0)
	{
		//进行运费计算
		$sub_total = 8;
		$total_fee = $total;
//        if($cart){
//            foreach($cart as $value){
//                if($value->product_id=='22189'){
//                    $total_fee=$total_fee-200;
//                    break;
//                }
//            }
//        }
		//判断是否满足店铺内包邮
		if ($store = Store::findOne(['store_id' => $store_id])) {
			//是否包邮（满X元包邮）
			if ($store->befreepostage == 1 && $store->minbookcash <= $total_fee) {
				$sub_total = 0;
			}
		}
		//判断商品是否为包邮商品或一元购商品
		if ($cart) {
			foreach ($cart as $value) {
				if ($value->product->baoyou) { //包邮活动
					$sub_total = 0;
					break;
				}
//				if ($value->promotion && $value->promotion->promotion->subject == 'YIYUANGOU') { //一元免邮
//					$sub_total = 0;
//					break;
//				}
			}
		}
		//判断是否使用自提点，若使用自己提供的自提点，免运费
		if ($delivery_station_id) {
			if ($deliverObj = PlatformStation::findOne(['id' => $delivery_station_id])) {
				if ($deliverObj->store_id == $store_id) {
					$sub_total = 0;
				}
			}
		}
		$shipping_cost = $sub_total;
		$total_data[] = [
			'code' => 'shipping',
			'title' => "固定运费",
			'value' => $sub_total,
			'sort_order' => 2
		];
		$total += $sub_total;
	}
	public function getGlobalCouponTotal(&$total_data, &$total, $cart, $store_id, $shipping_cost = 0, &$coupon_gift,&$rate){
		$model = \api\models\V1\Coupon::find()->where(['status'=>1,'is_open' => 0,'is_prize'=>0,'is_entity'=>0,'store_id'=>$store_id])
			->andWhere(["<=", "date_start", date('Y-m-d H:i:s', time())])
			->andWhere([">=", "date_end", date('Y-m-d H:i:s', time())])
			->orderBy('discount desc, date_end asc ,coupon_id desc')->groupBy('coupon_id')->all();
		if($model){
			foreach ($model as $coupon){
				$coupon_info=$this->getCoupon($coupon->coupon_id,$cart);
				if($coupon_info){
					$his_count=CouponHistory::find()->where(['coupon_id'=>$coupon_info->coupon_id,'customer_id'=>Yii::$app->user->getId()])->count();
					if($coupon_info->user_limit && $his_count>=$coupon_info->user_limit){
						continue;
					}
					$discount_total = 0;
					$sub_total = 0;
					$coupon_relate_product = [];
					$coupon_relate_product_data = [];
					if (!$coupon_info->product) {
						$sub_total = bcsub($total, $shipping_cost, 2);
					} else {
						//判断此券是否限制包装商品
						$coupon_product = [];
						if ($coupon_info->product) {
							foreach ($coupon_info->product as $value) {
								if ($value->status) {
									$coupon_product[] = $value->product_id;
								}
							}
						}
						if ($coupon_product) {
							foreach ($cart as $val) {
								if (in_array($val->product->product_id, $coupon_product)) {
									$coupon_relate_product[] = $val->product->product_id;
									$coupon_relate_product_data[] = $val;
									$sub_total += isset($rate[$val->product->product_id]) ? $rate[$val->product->product_id] : $val->getCost();
								}
							}
						}
					}
					if ($coupon_info->type == 'F') {
						$discount_total = min($coupon_info->discount, $sub_total);
					} elseif ($coupon_info['type'] == 'D') {
						$discount_total = bcmul($sub_total, $coupon_info->discount, 2);
					} elseif ($coupon_info['type'] == 'P') {
						$discount_total = bcmul($sub_total, $coupon_info->discount, 2);
					}
					if ($coupon_info->min_discount && $coupon_info->min_discount > $discount_total) {
						$discount_total = $coupon_info->min_discount;
					}
					if ($coupon_info->max_discount && $coupon_info->max_discount < $discount_total) {
						$discount_total = $coupon_info->max_discount;
					}
					if ($coupon_info->shipping && $shipping_cost) {
						$discount_total += $shipping_cost;
					}
					// If discount greater than total
					if ($discount_total > $total) {
						$discount_total = $total;
					}
					if ($discount_total) {
						if ($coupon_relate_product_data) {
							$group_rate = [];
							foreach ($coupon_relate_product_data as $key => $product_data) {
								if ($key == count($coupon_relate_product_data) - 1) {
									$t_total = 0;
									if ($group_rate) {
										foreach ($group_rate as $value) {
											$t_total = bcadd($t_total, $value, 2);
										}
									}
									$rate[$product_data->product->product_id] = round(bcsub(bcsub($sub_total, $discount_total, 2), $t_total, 2), 2);
								} else {
									$t_product_total = isset($rate[$product_data->product->product_id]) ? $rate[$product_data->product->product_id] : $product_data->getCost();
									$t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10);
									$t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
									$group_rate[$product_data->product->product_id] = round($t_product_cost, 2);
									$rate[$product_data->product->product_id] = round($t_product_cost, 2);
								}
							}
						} else {
							$order_rate = [];
							foreach ($cart as $key => $product) {
								if ($key == count($cart) - 1) {
									$t_total = 0;
									if ($order_rate) {
										foreach ($order_rate as $value) {
											$t_total = bcadd($t_total, $value, 2);
										}
									}
									$rate[$product->product->product_id] = round(bcsub(bcsub($sub_total, $discount_total, 2), $t_total, 2), 2);
								} else {
									$t_product_total = isset($rate[$product->product->product_id]) ? $rate[$product->product->product_id] : $product->getCost();
									$t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10);
									$t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
									$order_rate[$product->product->product_id] = round($t_product_cost, 2);
									$rate[$product->product->product_id] = round($t_product_cost, 2);
								}
							}
						}
						$this->order_product_rate[$coupon_info->store_id] = $rate;
					}
					if ($coupon_info->gift) {
						foreach ($coupon_info->gift as $gift) {
							//if($gift->product->$gift->qty<=$gift->product->getStockCount()){
								$coupon_gift[] = $gift;
							//}
						}
					}
					if($discount_total>0 || $coupon_info->gift){
						$total_data[] = [
							'code' => 'coupon',
							'title' => $coupon_info->name,
							'value' => -$discount_total,
							'sort_order' => 3,
							'code_id' => $coupon_info->coupon_id,
							'customer_coupon_id' => 0
						];
						$total -= $discount_total;
					}
				}
			}
		}
	}
	public function getCouponTotal(&$total_data, &$total, $cart, $customer_coupon_ids = [], $shipping_cost = 0, &$coupon_gift,&$rate)
	{
		if ($customer_coupon_ids = $this->FormartCouponIds($customer_coupon_ids)) {
			foreach ($customer_coupon_ids as $customer_coupon_id) {
				//验证用户折扣券
				$model = CustomerCoupon::find()->where(['customer_coupon_id' => $customer_coupon_id, 'is_use' => 0])->andWhere(["<=", "start_time", date('Y-m-d H:i:s', time())])->andWhere([">=", "end_time", date('Y-m-d H:i:s', time())])->one();
				if(!$model){
					continue;
				}
				//验证折扣券
				$coupon_info = $this->getCoupon($model->coupon_id, $cart);
				if ($coupon_info) {
					$discount_total = 0;
					$sub_total = 0;
					$coupon_relate_product = [];
					$coupon_relate_product_data = [];
					if (!$coupon_info->product) {
						$sub_total = bcsub($total, $shipping_cost, 2);
					} else {
						//判断此券是否限制包装商品
						$coupon_product = [];
						if ($coupon_info->product) {
							foreach ($coupon_info->product as $value) {
								if ($value->status) {
									$coupon_product[] = $value->product_id;
								}
							}
						}
						if ($coupon_product) {
							foreach ($cart as $val) {
								if (in_array($val->product->product_id, $coupon_product)) {
									$coupon_relate_product[] = $val->product->product_id;
									$coupon_relate_product_data[] = $val;
									$sub_total += isset($rate[$val->product->product_id]) ? $rate[$val->product->product_id] : $val->getCost();
								}
							}
						}
					}
					if ($coupon_info->type == 'F') {
						$discount_total = min($coupon_info->discount, $sub_total);
					} elseif ($coupon_info['type'] == 'D') {
						$discount_total = bcmul($sub_total, $coupon_info->discount, 2);
					} elseif ($coupon_info['type'] == 'P') {
						$discount_total = bcmul($sub_total, $coupon_info->discount, 2);
					}
					if ($coupon_info->min_discount && $coupon_info->min_discount > $discount_total) {
						$discount_total = $coupon_info->min_discount;
					}
					if ($coupon_info->max_discount && $coupon_info->max_discount < $discount_total) {
						$discount_total = $coupon_info->max_discount;
					}
					if ($coupon_info->shipping && $shipping_cost) {
						$discount_total += $shipping_cost;
					}
					// If discount greater than total
					if ($discount_total > $total) {
						$discount_total = $total;
					}
					if ($discount_total) {
						if ($coupon_relate_product_data) {
							$group_rate = [];
							foreach ($coupon_relate_product_data as $key => $product_data) {
								if ($key == count($coupon_relate_product_data) - 1) {
									$t_total = 0;
									if ($group_rate) {
										foreach ($group_rate as $value) {
											$t_total = bcadd($t_total, $value, 2);
										}
									}
									$rate[$product_data->product->product_id] = round(bcsub(bcsub($sub_total, $discount_total, 2), $t_total, 2), 2);
								} else {
									$t_product_total = isset($rate[$product_data->product->product_id]) ? $rate[$product_data->product->product_id] : $product_data->getCost();
									$t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10);
									$t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
									$group_rate[$product_data->product->product_id] = round($t_product_cost, 2);
									$rate[$product_data->product->product_id] = round($t_product_cost, 2);
								}
							}
						} else {
							$order_rate = [];
							foreach ($cart as $key => $product) {
								if ($key == count($cart) - 1) {
									$t_total = 0;
									if ($order_rate) {
										foreach ($order_rate as $value) {
											$t_total = bcadd($t_total, $value, 2);
										}
									}
									$rate[$product->product->product_id] = round(bcsub(bcsub($sub_total, $discount_total, 2), $t_total, 2), 2);
								} else {
									$t_product_total = isset($rate[$product->product->product_id]) ? $rate[$product->product->product_id] : $product->getCost();
									$t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10);
									$t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
									$order_rate[$product->product->product_id] = round($t_product_cost, 2);
									$rate[$product->product->product_id] = round($t_product_cost, 2);
								}
							}
						}
						$this->order_product_rate[$coupon_info->store_id] = $rate;
					}
					if ($coupon_info->gift) {
						foreach ($coupon_info->gift as $gift) {
							//if($gift->qty<=$gift->product->getStockCount()){
								$coupon_gift[] = $gift;
							//}
						}
					}
					if($discount_total>0 || $coupon_info->gift){
						$total_data[] = [
							'code' => 'coupon',
							'title' => $coupon_info->name,
							'value' => -$discount_total,
							'sort_order' => 3,
							'code_id' => $coupon_info->coupon_id,
							'customer_coupon_id' => $customer_coupon_id
						];
						$total -= $discount_total;
					}
				}
			}
		}
	}

	private function FormartCouponIds($customer_coupon_ids)
	{
		$result = [];
		if ($customer_coupon_ids) {
			//进行折扣券进行商品，分类、品牌进行排序
			foreach ($customer_coupon_ids as $customer_coupon_id) {
				if ($model = CustomerCoupon::findOne($customer_coupon_id)) {
					switch ($model->coupon->model) {
						case "ORDER":
							$result['order'] = [
								'id' => $customer_coupon_id,
								'sort' => 5
							];
							break;
						case "BUY_GIFTS":
							$result[] = [
								'id' => $customer_coupon_id,
								'sort' => 4
							];
							break;
						case "CLASSIFY";
							$result[] = [
								'id' => $customer_coupon_id,
								'sort' => 3
							];
							break;
						case "BRAND":
							$result[] = [
								'id' => $customer_coupon_id,
								'sort' => 2
							];
							break;
						default;
							$result[] = [
								'id' => $customer_coupon_id,
								'sort' => 1
							];
							break;
					}
				}
			}
			ArrayHelper::multisort($result, 'sort', SORT_ASC);
			$customer_coupon_ids = [];
			foreach ($result as $value) {
				$customer_coupon_ids[] = $value['id'];
			}
		}
		return $customer_coupon_ids;
	}

	public function getCoupon($coupon_id,$order_cart){
		$status = true;
		if($coupon=Coupon::findOne(['coupon_id'=>$coupon_id,'status'=>1])){
			$sub_qty = 0; //统计购物数量
			$sub_total = 0;//统计商品小计
			//判断此券是否限制包装商品
			if ($coupon->product) {
				$coupon_product = [];
				foreach ($coupon->product as $value) {
					if ($value->status) {
						$coupon_product[] = $value->product_id;
					}
				}
				if ($coupon_product) {
					$flag = false;
					foreach ($order_cart as $value) {
						if (in_array($value->product->product_id, $coupon_product)) {
							$sub_qty += $value->quantity;
							if (isset($this->order_product_rate[$value->product->store_id]) && $this->order_product_rate[$value->product->store_id] && isset($this->order_product_rate[$value->product->store_id][$value->product->product_id])) {
								$sub_total = bcadd($sub_total, $this->order_product_rate[$value->product->store_id][$value->product->product_id], 2);
							} else {
								$sub_total = bcadd($sub_total, $value->getCost(), 2);
							}
							$flag = true;
						}
					}
					if (!$flag) {
						$status = false;
					}
				}
			} else {
				$sub_qty = $this->SubQty($order_cart); //统计购物数量
				$sub_total = $this->SubTotal($order_cart);//统计商品小计
			}
			if ($coupon->limit_min_quantity && $coupon->limit_min_quantity > $sub_qty) {
				$status = false;
			}
			if ($coupon->limit_max_quantity && $coupon->limit_max_quantity < $sub_qty) {
				$status = false;
			}
			if ($coupon->total > 0 && $coupon->total > $sub_total) {
				$status = false;
			}
			if ($coupon->limit_max_total > 0 && $coupon->limit_max_total < $sub_total) {
				$status = false;
			}
			//判断券的使用次数
			$count = count($coupon->history);
			if ($coupon->quantity > 0 && $count >= $coupon->quantity) {
				$status = false;
			}
		}
		if ($status) {
			return $coupon;
		}else{
			return null;
		}
	}

	public function getOrderPromotions($store_id)
	{
		$promotions = PromotionOrder::find()->joinWith([
			'promotion' => function ($query) {
				$query->andFilterWhere(["jr_promotion.type" => 'ORDER', 'jr_promotion.status' => 1]);
			}
		])->where(['jr_promotion_order.store_id' => $store_id, 'jr_promotion_order.status' => 1])
			->andWhere(['and', "jr_promotion_order.begin_date<='" . date("Y-m-d H:i:s", time()) . "'", "jr_promotion_order.end_date>='" . date("Y-m-d H:i:s", time()) . "'"])
			->orderBy('jr_promotion_order.promotion_order_id desc')->all();
		return $promotions;
	}

	public function getOrderTotal(&$total_data, &$total, $cart, $store_id, &$promotion)
	{
		if ($orderDetails = $this->getOrderPromotions($store_id)) {
			foreach ($orderDetails as $promotion_order) {
				//进行小时约束过滤
				if ($promotion_order->need_hour && (strtotime(date("Y-m-d " . $promotion_order->date_start, time())) > time() || strtotime(date("Y-m-d " . $promotion_order->date_end, time())) < time())) {
					continue;
				}
				//进行数量、金额、数量金额、不限过滤
				$product_count = count($cart);
				if ($promotion_order->stairtype == "QTY" && ($promotion_order->begin_quantity > $product_count || $promotion_order->end_quantity < $product_count)) {
					continue;
				}
				$order_total = $total;
				if ($promotion_order->stairtype == "MONEY" && ($promotion_order->begin_amount > $order_total || $promotion_order->end_amount < $order_total)) {
					continue;
				}
				if ($promotion_order->stairtype == "ALL" && ($promotion_order->begin_quantity > $product_count || $promotion_order->end_quantity < $product_count || $promotion_order->begin_amount > $order_total || $promotion_order->end_amount < $order_total)) {
					continue;
				}
				//促销库存限制过滤 INV、QTY
				if ($promotion_order->uplimit_type == 'QTY') {
					$count = PromotionHistory::find()->where(['and',
						'promotion_type="ORDER"', 'status=1',
						'promotion_id=' . $promotion_order->promotion_id,
						'promotion_detail_id=' . $promotion_order->promotion_order_id,
					])->sum('quantity');
					if ($promotion_order->uplimit_quantity < ($count ? $count + 1 : 1)) {
						continue;
					}
				}
				//进行购买频次过滤 DAY，NONE，PROMOTION_TIME，ORDER
				if ($promotion_order->stop_buy_type == "DAY") {
					$order_count = PromotionHistory::find()->where(['and',
						'promotion_type="ORDER"', 'customer_id=' . Yii::$app->user->getId(), 'status=1',
						'promotion_id=' . $promotion_order->promotion_id,
						'promotion_detail_id=' . $promotion_order->promotion_order_id,
						'date_added>' . "'" . date('Y-m-d 00:00:00', time()) . "'", 'date_added<' . "'" . date('Y-m-d 23:59:59', time()) . "'"
					])->count();
					if ($promotion_order->stop_buy_quantity < ($order_count ? $order_count + 1 : 1)) {
						continue;
					}
				}
				if ($promotion_order->stop_buy_type == "PROMOTION_TIME") {
					$order_count = PromotionHistory::find()->where(['and',
						'promotion_type="ORDER"', 'customer_id=' . Yii::$app->user->getId(), 'status=1',
						'promotion_id=' . $promotion_order->promotion_id,
						'promotion_detail_id=' . $promotion_order->promotion_order_id,
						'date_added>' . "'" . $promotion_order->begin_date . "'", 'date_added<' . "'" . $promotion_order->end_date . "'"
					])->count();
					if ($promotion_order->stop_buy_quantity < ($order_count ? $order_count + 1 : 1)) {
						continue;
					}
				}
				//进行价格取值
				if ($promotion_order->pricetype == 'REBATEPRICE') {
					$discount_total = bcmul($order_total, 1 - $promotion_order->rebate, 2);
				} else {
					$discount_total = $promotion_order->price;
				}
				$promotion = $promotion_order;
				$order_promotion_rate = [];
				foreach ($cart as $key => $product) {
					if ($key == count($cart) - 1) {
						$t_total = 0;
						if ($order_promotion_rate) {
							foreach ($order_promotion_rate as $value) {
								$t_total = bcadd($t_total, $value, 2);
							}
						}
						$this->order_product_rate[$store_id][$product->product->product_id] = round(bcsub(bcsub($total, $discount_total, 2), $t_total, 2), 2);
					} else {
						if (isset($this->order_product_rate[$store_id]) && $this->order_product_rate[$store_id] && isset($this->order_product_rate[$store_id][$product->product->product_id])) {
							$t_product_total = $this->order_product_rate[$store_id][$product->product->product_id];
						} else {
							$t_product_total = $product->getCost();
						}
						$t_product_rate = bcdiv(bcsub($total, $discount_total, 2), $total, 10);
						$t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
						$order_promotion_rate[$product->product->product_id] = round($t_product_cost, 2);
						$this->order_product_rate[$store_id][$product->product->product_id] = round($t_product_cost, 2);
					}
				}
				$total_data[] = [
					'code' => 'order',
					'title' => "订单满减",
					'value' => -$discount_total,
					'sort_order' => 4,
					'code_id' => $promotion->promotion_id,
					'customer_coupon_id' => $promotion->promotion_order_id,
				];
				$total -= $discount_total;
				break;
			}
		}
	}

	public function getTotal(&$total_data, &$total)
	{
		$total_data[] = [
			'code' => 'total',
			'title' => "订单总计",
			'value' => max(0, $total),
			'sort_order' => 4
		];
	}

	//店铺商品金额
	protected function SubTotal($cart)
	{
		$sub_total = 0;
		if ($cart) {
			foreach ($cart as $value) {
				if (isset($this->order_product_rate[$value->product->store_id]) && $this->order_product_rate[$value->product->store_id] && isset($this->order_product_rate[$value->product->store_id][$value->product->product_id])) {
					$sub_total = bcadd($sub_total, $this->order_product_rate[$value->product->store_id][$value->product->product_id], 2);
				} else {
					$sub_total = bcadd($sub_total, $value->getCost(), 2);
				}
			}
		}
		return $sub_total;
	}

	//店铺商品数量
	protected function SubQty($cart)
	{
		$sub_qty = 0;
		if ($cart) {
			foreach ($cart as $value) {
				$sub_qty += $value->quantity;
			}
		}
		return $sub_qty;
	}

	public function actionOrderAjax()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		if ($store_id = Yii::$app->request->post('store_id')) {
			$delivery_station_id = Yii::$app->request->post('delivery_station_id') ? Yii::$app->request->post('delivery_station_id') : 0;
			$customer_coupon_ids = Yii::$app->request->post('customer_coupon_id') ? Yii::$app->request->post('customer_coupon_id') : 0;
			$customer_coupon_id=explode(',',$customer_coupon_ids);
			$comfirm_orders = Yii::$app->session->get('comfirm_orders');
			$data = isset($comfirm_orders[$store_id]['products']) ? $comfirm_orders[$store_id]['products'] : [];
			$total = 0;
			$totals = [];
			$rate=[];
			//计算商品金额
			$this->getSubTotal($totals, $total, $data);
			//计算运费金额
			$shipping_cost = 0;
			$this->getShippingTotal($totals, $total, $data, $store_id, $shipping_cost, $delivery_station_id);
			$coupon_gift = [];
			//计算全局优惠券金额
			$this->getGlobalCouponTotal($totals, $total, $data, $store_id, $shipping_cost, $coupon_gift,$rate);
			//计算优惠金额
			$this->getCouponTotal($totals, $total, $data, $customer_coupon_id, $shipping_cost, $coupon_gift,$rate);
			//计算订单优惠金额
			$promotion = [];
			$this->getOrderTotal($totals, $total, $data, $store_id, $promotion);
			//计算订单金额
			$this->getTotal($totals, $total);
			$json = [
				'status' => true,
				'data' => $this->renderPartial('totals', ['model' => $totals]),
				'store_promotion' => StorePromotion::widget(['promotion' => $promotion, 'coupon_gift' => $coupon_gift]),
			];
		} else {
			$json = [
				'status' => false,
				'data' => '参数错误'
			];
		}
		return Json::encode($json);
	}

	public function actionSelectAddress()
	{
		$json['result'] = false;
		$address_id = Yii::$app->request->post('address_id');
		if ($address_id) {
			$default_address = Address::find()->where(['customer_id' => \Yii::$app->user->getId(), 'address_id' => $address_id])->one();
			if (!empty($default_address)) {
				Yii::$app->session->remove('checkout_address_id');
				Yii::$app->session->set('checkout_address_id', $address_id);
				$json['result'] = true;
			} else {
				$json['result'] = false;
			}
		}
		return Json::encode($json);
	}

	public function actionShippingAddress()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		$model = new AddressForm(Yii::$app->request->get('address_id'));
		if ($model->load(Yii::$app->request->post())) {
			if ($address = $model->save()) {
				Yii::$app->session->set('checkout_address_id', $address->address_id);
				if (Yii::$app->request->isAjax) {
					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return ['success' => true];
				}
				return $this->redirect('/checkout/index');
			} else {
				if (Yii::$app->request->isAjax) {
					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return \yii\widgets\ActiveForm::validate($model);
				}
			}
		}
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('address', ['model' => $model]);
		} else {
			return $this->render('address', [
				'model' => $model,
			]);
		}
	}

	private function getOrderBlackScore($address_id)
	{
		$score = 0;
		if ($model = Address::findOne(['address_id' => $address_id])) {
			if ($black = OrderBlack::find()->where(['shipping_telephone' => $model->telephone, 'status' => 1])->sum("score")) {
				$score += 5;
			}
			if ($black = OrderBlack::find()->where(['shipping_username' => $model->firstname, 'status' => 1])->max("score")) {
				$score += 1;
			}
			if ($black = OrderBlack::find()->where(['shipping_address' => $model->address_1, 'status' => 1])->max("score")) {
				$score += 1;
			}
			if ($black = OrderBlack::find()->where(['ip' => Yii::$app->request->getUserIP(), 'status' => 1])->max("score")) {
				$score += 1;
			}
		}
		return $score;
	}
}
