<?php
namespace h5\controllers;
use api\models\V1\Address;
use api\models\V1\AdvertiseDetail;
use api\models\V1\Coupon;
use api\models\V1\CouponHistory;
use api\models\V1\CustomerCoupon;
use api\models\V1\Invoice;
use api\models\V1\OrderBlack;
use api\models\V1\OrderMerge;
use api\models\V1\PlatformStation;
use api\models\V1\PointCustomer;
use api\models\V1\PointCustomerFlow;
use api\models\V1\Product;
use api\models\V1\PromotionHistory;
use api\models\V1\PromotionOrder;
use common\behavior\NoCsrf;
use common\component\Helper\Xcrypt;
use h5\models\CheckoutForm;
use h5\models\ViewDeliveryForm;
use h5\widgets\Checkout\StorePromotion;
use Yii;
use api\models\V1\Store;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\httpclient\Client;
use yii\web\NotFoundHttpException;

class CheckoutController extends \yii\web\Controller {
	public $order_product_rate = [];
	public $order_product_paytotal = [];
	public $order_coupon_product_rate = [];
	private $merge_shipping_data=[];

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
		if (\Yii::$app->user->isGuest){
			return $this->redirect(['/site/login', 'redirect' => '/checkout/index','bind_status'=>1]);
		}
		if(!Yii::$app->user->identity->telephone_validate) {
			return $this->redirect(['/user/security-set-telephone', 'redirect' => '/checkout/index']);
		}
		if (!$cart = \Yii::$app->session->get('confirm_cart')){
			return $this->redirect('/cart/index');
		}
		//商品进行店铺拆分
		$cart_datas = [];
        $cart_warehouses = [];
		foreach ($cart as $value) {
			if (!$value->hasStock()) {
				return $this->redirect(['/cart/index']);
			}
			$cart_datas[$value->store_id][] = $value;

            $product = Product::findOne(['product_id'=>$value->product_id]);
            if($product && $product->warehouseStock){
                $cart_warehouses[$product->warehouseStock->warehouse_id][] = $value;//按仓库id分组
            }
		}

        //$stores_shipping = $this->getStoresShipping($cart_warehouses);


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

//                $shipping_cost = $stores_shipping[$key]['shipping_cost'];
//                $comfirm_orders[$key]['totals'][] = $this->setTotalsData("固定运费",'shipping',$shipping_cost,2);
//                $comfirm_orders[$key]['total'] = bcadd($comfirm_orders[$key]['total'],$shipping_cost,2);
//				$this->getShippingTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data, $key, $shipping_cost, $delivery_station_id);

				if ($coupons = Yii::$app->request->post('CheckoutForm')) {
					$customer_coupon_id = isset($coupons['coupon'][$key]) ? $coupons['coupon'][$key] : [];
				} else {
					$customer_coupon_id = [];
				}

                $shipping_cost_free = 0; //判断是否有免邮属性的优惠券
				//计算全局优惠券金额
				$this->getGlobalCouponTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data, $key, $shipping_cost, $comfirm_orders[$key]['coupon_gift'],$comfirm_orders[$key]['rate']);
				//计算优惠金额
				$this->getCouponTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data, $customer_coupon_id, $shipping_cost, $comfirm_orders[$key]['coupon_gift'],$comfirm_orders[$key]['rate'],$shipping_cost_free);
				//订单满减金额
				$this->getOrderTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data, $key, $comfirm_orders[$key]['promotion']);
				if($key ==1){
//                    $this->getPointsTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data);
                }
                $this->getShippingTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $cart_data, $key, $shipping_cost, $delivery_station_id,$shipping_cost_free);
				//应付订单金额
				$this->getTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total']);

			}
		}
        $mergeOrderShipFree=0;
		$proTotal=0;// be do Total
        if(count($comfirm_orders)>1){// not only stroe
            foreach ($comfirm_orders as $key=>&$val){
                $proTotal=bcadd($proTotal, $val['totals'][0]['value'], 2);
            }
            unset($val);
            if($proTotal>=68){ // ￥68 can free shipping
                foreach ($comfirm_orders as $key=>&$val){
                    if($val['totals'][1]['value']>0){
                        $val['total']=$val['total']-$val['totals'][1]['value'];
                        $val['totals'][2]['value']=$val['totals'][2]['value']-$val['totals'][1]['value'];
                        $val['totals'][1]['value']=0;
                    }
                }
            }else{
                $temporaryShipFree=5;
                foreach ($comfirm_orders as $key=>&$val){
                    if($val['totals'][1]['value']>0){
                        $val['total']=$val['total']-$temporaryShipFree;
                        $val['totals'][2]['value']=$val['totals'][2]['value']-$temporaryShipFree;
                        $val['totals'][1]['value']=$temporaryShipFree;
                    }
                }
                $mergeOrderShipFree=10;// most 10
            }
            unset($val);
        }else{
            foreach ($comfirm_orders as $key=>$val){
                $mergeOrderShipFree=$val['totals'][1]['value'];
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

		$model = new CheckoutForm($comfirm_orders, $this->order_product_paytotal,$this->order_coupon_product_rate);
			if ($model->load(Yii::$app->request->post()) && $trade_no = $model->submit()) {
				Yii::$app->session->remove('comfirm_orders');
				Yii::$app->session->remove('confirm_cart');
                Yii::$app->session->remove('customer_point_h5');
                Yii::$app->session->remove('checkout_address_id');
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
				return $this->redirect(['payment/index', 'trade_no' => $trade_no, 'showwxpaytitle' => 1]);
			}
        $data = [];
        $advertise = new AdvertiseDetail();
//		/*获取滚动banner*/
        $focus_position =   'H5-BYTC-DES1';
        $checkout_ad = $advertise->getAdvertiserDetailByPositionCode($focus_position);

		return $this->render('index', [
			'cart' => $comfirm_orders,
			'pay_total' => number_format($merge_order_total, 2),
			'model' => $model,
            'checkout_ad' => $checkout_ad
		]);
	}

	public function actionComplate()
	{
        usleep(1500000);//暂停1.5s
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		if (!$merge_code = \Yii::$app->request->get('trade_no')) {
			$merge_code = Yii::$app->session->get('Pay_trade_no');
		}
		if (!$model = OrderMerge::findOne(['merge_code' => $merge_code])) {
			return $this->redirect('/order/index');
		}
        $orders = $model->order;
		if($orders){
		    $ground_type = false;
		    foreach ($orders as $order){
		        if(strtolower($order->order_type_code) == 'groundpush'){
		            $order_model = $order;
		            $ground_type = true;
                }
            }
            if($ground_type){
                if($order_model && $order_model->order_status_id==2 && strtolower($order_model->order_type_code) == 'groundpush'){
                    $xcrypt = new Xcrypt('qwertyu8','ecb');
                    $en_code = $xcrypt->encrypt($order_model->order_id,'hex');
                }else{
                    $en_code = null;
                }
                return $this->render("complate-ground-push", ['order_model' => $order_model,'model' => $model,'en_code'=>$en_code]);
            }
        }
		return $this->render("complate", ['model' => $model]);
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
    public function getStoreSubTotal($cart){
        $sub_total = 0;
        if ($cart) {
            foreach ($cart as $value) {
                $sub_total = bcadd($sub_total, $value->getCost(), 2);
            }
        }

        return $sub_total;
    }
	public function getShippingTotal(&$total_data, &$total, $cart, $store_id = 0, &$shipping_cost, $delivery_station_id = 0,$shipping_cost_free = 0)
	{
		//进行运费计算
		$sub_total = 10;
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
		if($shipping_cost_free){
            $sub_total = 0;
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
	private function setTotalsData($title,$code,$value,$sort,$params=null){
        $data = [
            'code' => $code,
            'title' => $title,
            'value' => $value,
            'sort_order' => $sort
        ];
        if($params){
            foreach ($params as $key => $value){
                $data[$key] = $value;
            }
        }

        return $data;
    }
    //区别于 getStoresShipping方法
	private function getStoreShipping($subtotal,$store_id,$cart){
        //进行运费计算
        $shipping_total = 8;
        $total_fee = $subtotal;//商品总额
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
                $shipping_total = 0;
            }
        }
        //判断商品是否为包邮商品或一元购商品
        if ($cart) {
            foreach ($cart as $value) {
                if ($value->product->baoyou) { //包邮活动
                    $shipping_total = 0;
                    break;
                }
//				if ($value->promotion && $value->promotion->promotion->subject == 'YIYUANGOU') { //一元免邮
//					$shipping_total = 0;
//					break;
//				}
            }
        }
//        //判断是否使用自提点，若使用自己提供的自提点，免运费
//        if ($delivery_station_id) {
//            if ($deliverObj = PlatformStation::findOne(['id' => $delivery_station_id])) {
//                if ($deliverObj->store_id == $store_id) {
//                    $shipping_total = 0;
//                }
//            }
//        }
        return $shipping_total;
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
                    if($this->order_product_paytotal&& isset($this->order_product_paytotal[$store_id])){
                        $rate = $this->order_product_paytotal[$store_id];
                    }
                    $sub_total_default_status = true;//默认为sub_total赋值 订单总额

                    if($coupon_info->except_category){
                        $sub_total_default_status = false;
                        $sub_total = 0;
                        $except_array = explode(',',$coupon_info->except_category);
                        if($except_array){
                            foreach ($cart as $value) {
                                if ( !in_array($value->product->productBase->category_id,$except_array)) {
                                    if (isset($rate) && $rate && isset($rate[$value->product->product_id])) {
                                        $sub_total = bcadd($sub_total, $rate[$value->product->product_id], 2);
                                    } else {
                                        $sub_total = bcadd($sub_total, $value->getCost(), 2);
                                    }
                                    $coupon_relate_product_data[] = $value;
                                }
                            }
                        }
                    }
                    if ($coupon_info->product) {
                        $sub_total_default_status = false;
                        //判断此券是否限制包装商品
                        $sub_total = 0;
                        $coupon_product = [];
                        foreach ($coupon_info->product as $value) {
                            if ($value->status) {
                                $coupon_product[] = $value->product_id;
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
                    if($sub_total_default_status){
                        $sub_total = bcsub($total, $shipping_cost, 2); //默认值
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
					if ($discount_total >0) {
                        if ($coupon_relate_product_data) {
                            $group_rate = [];
                            $rate = [];
                            $rate_tmp = [];
                            foreach ($coupon_relate_product_data as $key => $product_data) {
                                $product_store_id = $product_data->product->store_id;
                                if($this->order_product_paytotal&& isset($this->order_product_paytotal[$product_store_id])){
                                    $rate = $this->order_product_paytotal[$product_store_id];
                                }else{
                                    $rate = [];
                                }

                                $t_product_total = isset($rate[$product_data->product->product_id]) ? $rate[$product_data->product->product_id] : $product_data->getCost(); //获取当前该商品实付金额 $t_product_total
                                $t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10);
                                $t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
                                $group_rate[$product_data->product->product_id] = round($t_product_cost, 2);
                                $t_product_discount = round(bcsub($t_product_total,$t_product_cost,4), 2);
                                $rate_tmp[$product_data->product->product_id] = round($t_product_discount, 2); //该折扣券优惠的金额 $t_product_discount
                                $this->order_product_paytotal[$product_store_id][$product_data->product->product_id] = $t_product_total - $t_product_discount;//该商品当前实付金额

                            }
                        } else {
                            $order_rate = [];
                            $rate = [];
                            $rate_tmp = [];
                            foreach ($cart as $key => $product) {
                                $product_store_id = $product->product->store_id;
                                if($this->order_product_paytotal&& isset($this->order_product_paytotal[$product_store_id])){
                                    $rate = $this->order_product_paytotal[$product_store_id];
                                }else{
                                    $rate = [];
                                }
                                $t_product_total = isset($rate[$product->product->product_id]) ? $rate[$product->product->product_id] : $product->getCost();
                                $t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10); //8折 X折优惠券 比率
                                $t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
                                $order_rate[$product->product->product_id] = round($t_product_cost, 2);
                                //$rate[$product->product->product_id] = round($t_product_cost, 2);
                                $t_product_discount = round(bcsub($t_product_total,$t_product_cost,4), 2);;//改折扣券 优惠的金额 $t_product_discount
                                $rate_tmp[$product->product->product_id] = round($t_product_discount, 2);
                                $this->order_product_paytotal[$product_store_id][$product->product->product_id] = $t_product_total - $t_product_discount;//该商品当前实付金额

                            }
                        }
                        $this->order_coupon_product_rate[$coupon_info->coupon_id] = $rate_tmp;
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
							'customer_code_id' => 0
						];
						$total -= $discount_total;
					}
				}
			}
		}
	}
	public function getCouponTotal(&$total_data, &$total, $cart, $customer_coupon_ids = [], $shipping_cost = 0, &$coupon_gift,&$rate,&$shipping_cost_free = 0)
	{
		if ($customer_coupon_ids = $this->FormartCouponIds($customer_coupon_ids)) {
            $coupon_array = [];
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
                    $sub_total_default_status = true;//默认为sub_total赋值 订单总额

                    if($coupon_info->except_category){
                        $sub_total_default_status = false;
                        $sub_total = 0;
                        $except_array = explode(',',$coupon_info->except_category);
                        if($except_array){
                            foreach ($cart as $value) {
                                if ( !in_array($value->product->productBase->category_id,$except_array)) {
                                    if (isset($this->order_product_paytotal[$value->product->store_id]) && $this->order_product_paytotal[$value->product->store_id] && isset($this->order_product_paytotal[$value->product->store_id][$value->product->product_id])) {
                                        $sub_total = bcadd($sub_total, $this->order_product_paytotal[$value->product->store_id][$value->product->product_id], 2);
                                    } else {
                                        $sub_total = bcadd($sub_total, $value->getCost(), 2);
                                    }
                                    $coupon_relate_product_data[] = $value;
                                }
                            }
                        }
                    }


                    if ($coupon_info->product) {
                        $sub_total_default_status = false;
                        //判断此券是否限制包装商品
                        $sub_total = 0;
                        $coupon_product = [];
                        foreach ($coupon_info->product as $value) {
                            if ($value->status) {
                                $coupon_product[] = $value->product_id;
                            }
                        }
                        if ($coupon_product) {
                            foreach ($cart as $val) {
                                if (in_array($val->product->product_id, $coupon_product)) {
                                    $coupon_relate_product[] = $val->product->product_id;
                                    $coupon_relate_product_data[] = $val;
                                    $sub_total += isset($this->order_product_paytotal[$val->product->store_id][$val->product->product_id]) ? $this->order_product_paytotal[$val->product->store_id][$val->product->product_id] : $val->getCost();
                                }
                            }
                        }
					}
                    if($sub_total_default_status){
                        $sub_total = bcsub($total, $shipping_cost, 2); //默认值
                    }
                    if($sub_total > 0){
                        if ($coupon_info->type == 'F') {
                            $discount_total = min($coupon_info->discount, $sub_total);
                        } elseif ($coupon_info['type'] == 'D') {
                            $discount_total = bcmul($sub_total, $coupon_info->discount, 2);
                        } elseif ($coupon_info['type'] == 'P') {
                            $discount_total = bcmul($sub_total, $coupon_info->discount, 2);
                        }
                    }else{
                        $discount_total = 0;
                    }

					if ($coupon_info->min_discount && $coupon_info->min_discount > $discount_total) {
						$discount_total = $coupon_info->min_discount;
					}
					if ($coupon_info->max_discount && $coupon_info->max_discount < $discount_total) {
						$discount_total = $coupon_info->max_discount;
					}
//					if ($coupon_info->shipping && $shipping_cost) {
//						$discount_total += $shipping_cost;
//					}
					if ($coupon_info->shipping) {
                        $shipping_cost_free = 1;
					}
					// If discount greater than total
					if ($discount_total > $total) {
						$discount_total = $total;
					}
					if ($discount_total >0) {
						if ($coupon_relate_product_data) {
							$group_rate = [];
                            $rate = [];
                            $rate_tmp = [];
							foreach ($coupon_relate_product_data as $key => $product_data) {
							    if($this->order_product_paytotal&& isset($this->order_product_paytotal[$product_data->store_id])){
                                    $rate = $this->order_product_paytotal[$product_data->store_id];
                                }else{
							        $rate = [];
                                }

                                $t_product_total = isset($rate[$product_data->product->product_id]) ? $rate[$product_data->product->product_id] : $product_data->getCost(); //获取当前该商品实付金额 $t_product_total
                                $t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10);
                                $t_product_cost = round(bcmul($t_product_rate, $t_product_total, 10),2);
                                $group_rate[$product_data->product->product_id] = round($t_product_cost, 2);
                                $t_product_discount = round(bcsub($t_product_total,$t_product_cost,4), 2);
                                $rate_tmp[$product_data->product->product_id] = $t_product_discount; //该折扣券优惠的金额 $t_product_discount
                                $this->order_product_paytotal[$product_data->store_id][$product_data->product->product_id] = $t_product_total - $t_product_discount;//该商品当前实付金额

							}
						} else {
							$order_rate = [];
                            $rate = [];
                            $rate_tmp = [];
							foreach ($cart as $key => $product) {
                                if($this->order_product_paytotal&& isset($this->order_product_paytotal[$product->store_id])){
                                    $rate = $this->order_product_paytotal[$product->store_id];
                                }else{
                                    $rate = [];
                                }

                                $t_product_total = isset($rate[$product->product->product_id]) ? $rate[$product->product->product_id] : $product->getCost();
                                $t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10); //8折 X折优惠券 比率
                                $t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
                                $order_rate[$product->product->product_id] = round($t_product_cost, 2);
                                $t_product_discount = round(bcsub($t_product_total,$t_product_cost,4), 2);//该折扣券 优惠的金额 $t_product_discount
                                $rate_tmp[$product->product->product_id] = $t_product_discount;
                                $this->order_product_paytotal[$product->store_id][$product->product->product_id] = $t_product_total - $t_product_discount;//该商品当前实付金额

							}
						}
						$this->order_coupon_product_rate[$coupon_info->coupon_id] = $rate_tmp;
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
							'customer_code_id' => $customer_coupon_id
						];
						$total -= $discount_total;
					}
					$coupon_array[] = $customer_coupon_id;
					//Yii::$app->session->set('customer_use_coupon_h5',$array);
				}
			}
			return $coupon_array;
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
            $sub_total_default_status = true;
		    //判断此券是否限制包装商品
		    if ($coupon->product) {
                $sub_total_default_status = false;
			    $coupon_product = [];
			    foreach ($coupon->product as $value) {
				    if ($value->status) {
					    $coupon_product[] = $value->product_id;
				    }
			    }
                foreach ($order_cart as $value) {
                    if (in_array($value->product->product_id, $coupon_product) ) {
                        $sub_qty += $value->quantity;
                        if (isset($this->order_coupon_product_rate[$coupon_id]) && $this->order_coupon_product_rate[$coupon_id] && isset($this->order_coupon_product_rate[$coupon_id][$value->product->product_id])) {
                            $sub_total = bcadd($sub_total, $this->order_coupon_product_rate[$coupon_id][$value->product->product_id], 2);
                        } else {
                            $sub_total = bcadd($sub_total, $value->getCost(), 2);
                        }
                    }
                }
		    }
            if($coupon->except_category){
                $sub_total_default_status = false;
                $except_array = explode(',',$coupon->except_category);
                foreach ($order_cart as $value) {
                    if ( !in_array($value->product->productBase->category_id,$except_array)) {
                        $sub_qty += $value->quantity;
                        if (isset($this->order_coupon_product_rate[$coupon_id]) && $this->order_coupon_product_rate[$coupon_id] && isset($this->order_coupon_product_rate[$coupon_id][$value->product->product_id])) {
                            $sub_total = bcadd($sub_total, $this->order_coupon_product_rate[$coupon_id][$value->product->product_id], 2);
                        } else {
                            $sub_total = bcadd($sub_total, $value->getCost(), 2);
                        }
                    }
                }
            }

            if($sub_total_default_status){
                $sub_qty = $this->SubQty($order_cart); //统计购物数量
                $sub_total = $this->SubTotal($order_cart);//统计商品小计
            }
            if($sub_total <= 0){
                $status = false;
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
                    if($this->order_product_paytotal&& isset($this->order_product_paytotal[$product->product->store_id])){
                        $rate = $this->order_product_paytotal[$product->product->store_id];
                    }else{
                        $rate = [];
                    }
                        if ($rate && isset($rate[$product->product->product_id])) {
                            $t_product_total = $rate[$product->product->product_id];//当前实付金额 ，计算coupon后的实付金额
                        } else {
                            $t_product_total = $product->getCost();
                        }

                        $t_product_rate = bcdiv(bcsub($total, $discount_total, 2), $total, 10);
                        $t_product_cost = round(bcmul($t_product_rate, $t_product_total, 10),2);//最终实付金额
                        $order_promotion_rate[$product->product->product_id] = round($t_product_cost, 2);
                        $this->order_product_paytotal[$store_id][$product->product->product_id] = $t_product_cost;
                        $this->order_product_rate[$store_id][$product->product->product_id] = round($t_product_cost, 2);



				}
				$total_data[] = [
					'code' => 'order',
					'title' => "订单满减",
					'value' => -$discount_total,
					'sort_order' => 4,
					'code_id' => $promotion->promotion_id,
					'customer_code_id' => $promotion->promotion_order_id,
				];
				$total -= $discount_total;
				break;
			}
		}
	}
    public function getPointsTotal(&$total_data, &$total,$cart)
    {

        if($total>0){
            $setArray = Yii::$app->session->get('customer_point_h5');
            if($setArray){
                foreach ($setArray as $point_customer_id => $v){
                    $point_customer = PointCustomer::findOne(['point_customer_id'=>$point_customer_id]);
                    if($point_customer){
                        $point_total = 0;
                        if($point_customer->point && $point_customer->point->init_points_url){
                            $point_total = $point_customer->point->pointByCurl;
                        }
                        $rate = $point_customer->point->rate;
                        if($point_total > 0){
                            $equal_balance = bcmul($point_total,$rate,2);//bcmul 舍去法保留两位数
                            if($equal_balance > $total){
                                $use_equal_balance = $total;
                            }else{
                                $use_equal_balance =  $equal_balance;
                            }

                            if ($use_equal_balance > 0) {

                                $total_data[] = array(
                                    'code'       => 'points',
                                    'title'      =>  $point_customer->point->display_name.'抵扣',
                                    'value'      => -$use_equal_balance,
                                    'sort_order' => 6,
                                    'code_id' => $point_customer->point_id,
                                    'customer_code_id' => $point_customer->point_customer_id
                                );
                                $total -= $use_equal_balance;
                            }
                        }

                    }
                }
            }
        }
    }
	public function getTotal(&$total_data, &$total)
	{
		$total_data[] = [
			'code' => 'total',
			'title' => "实付总计",
			'value' => max(0, $total),
			'sort_order' => 10
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
			$customer_coupon_id = Yii::$app->request->post('customer_coupon_id') ? Yii::$app->request->post('customer_coupon_id') : 0;
			$comfirm_orders = Yii::$app->session->get('comfirm_orders');
			$data = isset($comfirm_orders[$store_id]['products']) ? $comfirm_orders[$store_id]['products'] : [];
            $cart_warehouses = [];
			foreach ($comfirm_orders as $confirm_order){
			    if($confirm_order){
			        foreach ($confirm_order['products'] as $cart_position){
			            $product = Product::findOne(['product_id'=>$cart_position->product_id]);
                        if($product && $product->warehouseStock){
                            $cart_warehouses[$product->warehouseStock->warehouse_id][] = $cart_position;//按仓库id分组
                        }
                    }
                }

            }
            //$stores_shipping = $this->getStoresShipping($cart_warehouses);
			$total = 0;
			$totals = [];
			$rate=[];
			//计算商品金额
			$this->getSubTotal($totals, $total, $data);
			//计算运费金额
			$shipping_cost = 0;
//            $shipping_cost = $stores_shipping[$store_id]['shipping_cost'];
//            $totals[] = $this->setTotalsData("固定运费",'shipping',$shipping_cost,2);
//            $total = bcadd($comfirm_orders[$store_id]['total'],$shipping_cost,2);

            $shipping_cost_free = 0;
			$coupon_gift = [];
			//计算全局优惠券金额
			$this->getGlobalCouponTotal($totals, $total, $data, $store_id, $shipping_cost, $coupon_gift,$rate);
			//计算优惠金额
            $coupon_array = $this->getCouponTotal($totals, $total, $data, $customer_coupon_id, $shipping_cost, $coupon_gift,$rate,$shipping_cost_free);
			//计算订单优惠金额
			$promotion = [];
			$this->getOrderTotal($totals, $total, $data, $store_id, $promotion);

//            $this->getPointsTotal($totals, $total, $data);
            $this->getShippingTotal($totals, $total, $data, $store_id, $shipping_cost, $delivery_station_id,$shipping_cost_free);
			//计算订单金额
			$this->getTotal($totals, $total);
            $comfirm_orders=Yii::$app->session->get('comfirm_orders');
            $subTotal=0;// 抵扣总额
            foreach ($totals as $v){
                if($v['code'] == 'sub_total'){
                    $subTotal = bcadd($subTotal,$v['value'],2);
                }
            }
            unset($v);
            // 重新计算 合店商品
            $proTotal=0;// be do Total
            foreach ($comfirm_orders as $key=>$val){
                $proTotal=bcadd($proTotal, $val['totals'][0]['value'], 2);

            }
            Yii::error('mengyh');
            Yii::error($proTotal);
            Yii::error($subTotal);
            $proTotal=$proTotal+$subTotal;// 总额=商品总额+抵扣总额(负数)
            Yii::error($proTotal);
            if($proTotal>=68){
                foreach ($totals as &$v){
                    if($v['code']=='shipping'){
                        $v['value']=0;
                    }
                }
                unset($v);
            }else{
                foreach ($totals as &$v){
                    if($v['code']=='shipping'){
                        $v['value']=5;
                    }
                }
                unset($v);
            }



            $json = [
				'status' => true,
				'data' => $this->renderPartial('totals', ['model' => $totals,]),
				'store_promotion' => StorePromotion::widget(['promotion' => $promotion, 'coupon_gift' => $coupon_gift]),
                'coupon_array'=>$coupon_array,
                'shipping_cost'=>$shipping_cost,
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
		try{
		if(Yii::$app->request->isAjax && Yii::$app->request->isPost) {
			if ($address_id = Yii::$app->request->post('address_id')) {
				$default_address = Address::findOne(['customer_id' => \Yii::$app->user->getId(), 'address_id' => $address_id]);
				if ($default_address) {
					Yii::$app->session->remove('checkout_address_id');
					Yii::$app->session->set('checkout_address_id', $address_id);
					$data = ['result' => true, 'html' => $this->renderAjax('select-address',['model'=>$default_address])];
				} else {
					throw new ErrorException('数据加载失败');
				}
			}
		}else{
			throw new ErrorException('数据加载失败');
		}
		} catch (ErrorException $e) {
			$data = ['result' => false, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
    public function actionSelectInvoice()
    {
        try{
            if(Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                if ($invoice_id = Yii::$app->request->post('invoice_id')) {
                    $default_invoice = Invoice::findOne(['customer_id' => \Yii::$app->user->getId(), 'invoice_id' => $invoice_id]);
                    if ($default_invoice) {
                        Yii::$app->session->remove('checkout_invoice_id');
                        Yii::$app->session->set('checkout_invoice_id', $invoice_id);
                        $data = ['result' => true, 'html' => $this->renderAjax('select-invoice',['model'=>$default_invoice])];
                    } else {
                        throw new ErrorException('数据加载失败');
                    }
                }else{
                    Yii::$app->session->remove('checkout_invoice_id');
                    $data = ['result' => true, 'html' => $this->renderAjax('select-invoice',['model'=>null])];
                }
            }else{
                throw new ErrorException('数据加载失败');
            }
        } catch (ErrorException $e) {
            $data = ['result' => false, 'message' => $e->getMessage()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
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
	//计算每个店铺均摊的运费 区别于getStoreShipping方法
	private function getStoresShipping($cart_warehouses){
        if($cart_warehouses){ //
            $stores_shipping = [];

            foreach ($cart_warehouses as  $cart_warehouse){
                $baoyou = false;
                $cart_stores = [];
                foreach ($cart_warehouse as $value) {
                    if ($value->product->baoyou) { //包邮活动
                        $baoyou = true;
                    }
                    $cart_stores[$value->store_id][] = $value;
                }
                if($baoyou){ //该仓库内商品 有一个为包邮商品，则全部商品包邮
                    foreach ($cart_stores as $store_id => $cart_store){
                        $stores_shipping[$store_id]['shipping_cost'] = 0;
                    }
                }else{
                    //不包邮的情况，计算运费 满68则 该仓库商品包邮；低于68则 该仓库商品均摊最高运费

                    $warehouse_total = 0; //某仓库下所有商品总额
                    $call_back = function ($cartPosition,$key) use(&$warehouse_total) {
                        $warehouse_total = bcadd($warehouse_total,$cartPosition->getCost(),2);
                    };
                    array_walk($cart_warehouse,$call_back);



                    //整理 数据： $stores  $max_shipping（最高邮费金额）  $total（订单总额）
                    $max_shipping = 0; //计算同一个仓库，不同店铺中最高运费

                    $total = 0;
                    if($cart_stores){
                        $call_back3 = function ($cart_store,$sid) use (&$stores_shipping,&$max_shipping,&$total){
                            $subtotals = $this->getStoreSubTotal($cart_store);
                            $shipping_cost = $this->getStoreShipping($subtotals,$sid,$cart_store);
                            $stores_shipping[$sid] = [
                                'subtotal' => $subtotals,
                                'shipping_cost' => $shipping_cost
                            ];
                            $total = bcadd($total,$subtotals,2);

                            $max_shipping = max($shipping_cost,$max_shipping);
                        };
                        array_walk($cart_stores,$call_back3);
                    }

                    if($warehouse_total < 68){
                        //分配N个store的运费，按照subtotal的比例
                        if($stores_shipping){
                            foreach ($stores_shipping as $store_id => $value){
                                $rate = bcdiv($value['subtotal'],$total,4);
                                //$mul = bcmul($rate,$max_shipping,4);
                                $shipping = round(bcmul($rate,$max_shipping,4) );
                                $stores_shipping[$store_id]['shipping_cost'] = $shipping;
                            }
                        }
                    }else{
                        //同一个仓库 订单满68则包邮
                        foreach ($stores_shipping as $store_id => $value){
                            $stores_shipping[$store_id]['shipping_cost'] = 0;
                        }

                    }

                }
            }
            return $stores_shipping;

        }
    }
    public function actionPoints(){
	    $point_customer_id = Yii::$app->request->post('point_customer_id');
	    $point_customer = PointCustomer::findOne(['point_customer_id'=>$point_customer_id]);
	    try{

            if($point_customer ){
                $point_name = $point_customer->point->display_name;

                    $setArray = Yii::$app->session->get('customer_point_h5');
                    if($setArray && isset($setArray[$point_customer_id]) && $setArray[$point_customer_id]){
                        unset($setArray[$point_customer_id]);
                        $result['msg'] = '删除成功';
                        $result['action'] = 'remove';
                        $result['point_name'] = $point_name;
                        $result['point_customer_id'] = $point_customer_id;
                        //$result['value'] = -bcmul($point_customer->point->rate,$point_total,2); //积分能抵扣的钱数；

                    }else{
                        $point_total = $point_customer->point->pointByCurl;
                        if($point_total >0){
                            $setArray[$point_customer_id] = true;
                            $result['action'] = 'add';
                            $result['point_name'] = $point_name;
                            $result['point_customer_id'] = $point_customer_id;
                            //$result['value'] = -bcmul($point_customer->point->rate,$point_total,2); //积分能抵扣的钱数；
                        }
                }

                if($setArray){
                    Yii::$app->session->set('customer_point_h5',$setArray);
                }else{
                    Yii::$app->session->remove('customer_point_h5');
                }
            }
            return json_encode($result);
        }catch (ErrorException $e){
            Yii::error('checkout/points - $point_customer_id:'.$point_customer_id.' ============================>'.$e->getMessage());
        }



    }
}
