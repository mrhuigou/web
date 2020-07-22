<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/12/1
 * Time: 13:18
 */
namespace h5\models;
use api\models\V1\Address;
use api\models\V1\Affiliate;
use api\models\V1\AffiliateCustomer;
use api\models\V1\AffiliatePersonal;
use api\models\V1\CouponHistory;
use api\models\V1\Customer;
use api\models\V1\CustomerAffiliate;
use api\models\V1\CustomerCommission;
use api\models\V1\CustomerCommissionFlow;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerFollower;
use api\models\V1\Invoice;
use api\models\V1\Order;
use api\models\V1\OrderBlack;
use api\models\V1\OrderCoupon;
use api\models\V1\OrderGift;
use api\models\V1\OrderHistory;
use api\models\V1\OrderMerge;
use api\models\V1\OrderProduct;
use api\models\V1\OrderShipping;
use api\models\V1\OrderTotal;
use api\models\V1\PlatformStation;
use api\models\V1\Point;
use api\models\V1\PointCustomer;
use api\models\V1\PointCustomerFlow;
use api\models\V1\Product;
use api\models\V1\ProductBase;
use api\models\V1\PromotionHistory;
use api\models\V1\ReturnBase;
use api\models\V1\ReturnProduct;
use api\models\V1\WarehouseStock;
use common\component\Helper\OrderSn;
use common\models\User;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use yii\db\StaleObjectException;
use yii\log\Logger;
use yii\web\NotFoundHttpException;

class CheckoutForm extends Model {
	public $address_id;
	public $invoice_type = 0;
	public $invoice_title;
	public $invoice_value;
	public $invoice_address;//转票 地址和电话
    public $invoice_bank;// 开户行 及 账号
	public $delivery;
	public $comment;
	public $coupon;
	private $cart_data;
	public $order_product_rate = [];
	public $order_coupon_product_rate = [];
	public $order_product_paytotal = [];
	public $invoice_id;

	public function __construct($comfirm_orders, $order_product_paytotal = [],$order_coupon_product_rate=[], $config = [])
	{
		if (\Yii::$app->session->get('checkout_address_id')) {
			$this->address_id = \Yii::$app->session->get('checkout_address_id');
		}else{
			if(\Yii::$app->user->identity->address_id){
				if($address=Address::findOne(\Yii::$app->user->identity->address_id)){
					if($address->ifInRange){
                        $this->address_id =\Yii::$app->user->identity->address_id;
                    }else{
                        User::updateAll(['address_id'=>0],['customer_id'=>Yii::$app->user->getId()]);
                    }
				}else{
					//更新
					User::updateAll(['address_id'=>0],['customer_id'=>Yii::$app->user->getId()]);
				}
			}else{
				if($address=Address::findOne(['customer_id'=>Yii::$app->user->getId()])){
                    if($address->ifInRange){
                        $this->address_id=$address->address_id;
                    }
				}
			}
		}
        if (\Yii::$app->session->get('checkout_invoice_id')) {
            $this->invoice_id = \Yii::$app->session->get('checkout_invoice_id');
        }else{
		    $this->invoice_id = 0;//不需要发票
        }
		if ($comfirm_orders) {
			$this->cart_data = $comfirm_orders;
		} else {
			throw new InvalidParamException("数据错误");
		}
		$this->order_product_paytotal = $order_product_paytotal;
		$this->order_coupon_product_rate = $order_coupon_product_rate;
		parent::__construct($config);
	}

	public function rules()
	{
		return [
			[['address_id'], 'required'],
			[['invoice_value','invoice_address','invoice_bank', 'comment', 'coupon','invoice_title'], 'safe'],
			[['address_id', 'delivery'], 'safe'],
			[['address_id', 'delivery'], 'FormValidate'],
			//[['invoice_type'],'ValidateInvoice']
		];
	}
//	public function ValidateInvoice($attribute, $params){
//		if($this->invoice_type){
//			if(!$this->invoice_title){
//				$this->addError('invoice_title', '个人姓名或单位名称不能为空!');
//			}
//			if(!$this->invoice_value){
//				$this->addError('invoice_value', '身份证号或税号不能为空!');
//			}else{
//				if($this->invoice_type==1){
//					if(strlen($this->invoice_value)!==18){
//						$this->addError('invoice_value', '身份证号必须是18位!');
//					}
//				}
//				if($this->invoice_type==2){
//					if(strlen($this->invoice_value)!==15 && strlen($this->invoice_value)!==18 && strlen($this->invoice_value)!==20){
//						$this->addError('invoice_value', '单位税号必须是15、18位或20位!');
//					}
//				}
//                if($this->invoice_type==3){
//                    if(!$this->invoice_value){
//                        $this->addError('invoice_value', '纳税人识别号不能为空!');
//                    }
//                    if(strlen($this->invoice_value)!==15 && strlen($this->invoice_value)!==18 && strlen($this->invoice_value)!==20){
//                        $this->addError('invoice_value', '单位税号必须是15、18位或20位!');
//                    }
//                    if(!$this->invoice_address){
//                        $this->addError('invoice_address', '注册地址和电话不能为空!');
//                    }
//                    if(!$this->invoice_bank){
//                        $this->addError('invoice_bank', '开户行及账号不能为空!');
//                    }
//
//                }
//
//			}
//		}
//	}
	public function FormValidate($attribute, $params)
	{
		if (!$model = Address::findOne(['address_id' => $this->address_id, 'customer_id' => Yii::$app->user->getId()])) {
			$this->addError('address_id', '收货地址不能为空!');
		}
		if (!$this->delivery || !$this->delivery) {
			$this->addError('delivery', '配送时间不能为空!');
			Yii::$app->getSession()->setFlash('error', '配送时间不能为空！');
		}
	}

	public function attributeLabels()
	{
		return [
			'address_id' => '收货地址',
			'invoice_type' => '发票',
			'invoice_item'=>'发票明细',
			'invoice_title'=>'个人/企业',
			'invoice_value' => '身份证号/企业税号',
			'delivery' => '配送方式',
			'comment' => '备注',
			'coupon' => '店铺优惠券',
		];
	}

	public function getOrderType($cart)
	{
		$type = "normal";
		foreach ($cart as $product) {
			if ($product->product->bepresell) {
				$type = "presell";
				break;
			}
		}
		return $type;
	}


	public function submit()
	{   //交易号
		$trade_no = "";
        $comfirmOrders = $this->cart_data;//原始session
        $mergeOrder = Yii::$app->session->get('can_merge_orders');
        if($mergeOrder['isOrderMerge']){ //有合单
            $newMerge=array();
            $newMerge['base']=[];
            $mergeList=$mergeOrder['mergeList'];
            $noMergeList=$mergeOrder['noMergeList'];
            $mergeTotals=$mergeOrder['mergeTotals'];
            $newPromotion=[];
            $newProducts=[];
            $newCouponGift=[];
            $newTotal=0; // 订单总额
            $newSubTotal=0;//商品总额
            $newCouponArr=[];//优惠券
            foreach ($mergeList as $v){
                if(empty($newMerge['base'])){
                    $newMerge['base']=$v['base'];
                }
                $newPromotion=array_merge($newPromotion,$v['promotion']);
                $newProducts=array_merge($newProducts,$v['products']);
                $newCouponGift=array_merge($newCouponGift,$v['coupon_gift']);
                $newTotal=bcadd($newTotal, $v['total'], 2);
                if($v['totals']){
                    foreach ($v['totals'] as $m){
                        if($m['code'] == 'sub_total'){
                            $newSubTotal=bcadd($newSubTotal, $m['value'], 2);//商品总额 sub_total
                        }
                        if($m['code'] == 'coupon'){
                            $newCouponArr[]=$m;// coupon 优惠券
                        }
                    }
                }
            }
            $newTotal=bcadd($newTotal, $mergeTotals['shopping'], 2);
            $newMerge['products']=$newProducts;// products
            $newMerge['promotion']=$newPromotion; // promotion
            $newMerge['rate']=[];                 // rate
            $newMerge['coupon_gift']=$newCouponGift;// coupon_gift
            $newMerge['total']=$newTotal;// total
            $newMerge['totals']=array(
                array(
                    'code' => 'sub_total',
                    'title' => '商品总额',
                    'value' => $newSubTotal,
                    'sort_order' => 1,
                ),
                array(
                    'code' => 'shipping',
                    'title' => '固定运费',
                    'value' => $mergeTotals['shopping'],
                    'sort_order' => 2,
                ),
                array(
                    'code' => 'total',
                    'title' => '实付总计',
                    'value' => $newTotal,
                    'sort_order' => 10,
                ),

            );// totals
            $newMerge['totals']=array_merge($newMerge['totals'],$newCouponArr);
            array_push($noMergeList,$newMerge);
            $comfirmOrders=$noMergeList;

        }
		if ($this->validate()) {
			$merge_order_ids = [];
			$merge_total = 0;
			$product_stock = [];
			$transaction = \Yii::$app->db->beginTransaction();
			try {
				foreach ($comfirmOrders as $k => $order_data) {
					$invoice_temp = ['不需要发票', '个人发票', '企业增值税普票','企业增值税专票'];
					//订单主数据
					$Order_model = new Order();
					$Order_model->order_no = OrderSn::generateNumber();
					$Order_model->order_type_code = $this->getOrderType($order_data['products']);
					$Order_model->platform_id = $order_data['base']->platform_id;
					$Order_model->platform_name = "每日惠购";
					$Order_model->platform_url = Yii::$app->request->getHostInfo();
					$Order_model->store_id = $order_data['base']->store_id;
					$Order_model->store_name = $order_data['base']->name;
					$Order_model->store_url = $order_data['base']->url;
					$Order_model->customer_group_id = Yii::$app->user->identity['customer_group_id'];
					$Order_model->customer_id = Yii::$app->user->getId();
					$Order_model->firstname = Yii::$app->user->identity['firstname'];
					$Order_model->lastname = Yii::$app->user->identity['lastname'];
					$Order_model->email = Yii::$app->user->identity['email'];
					$Order_model->telephone = Yii::$app->user->identity['telephone'];
					$Order_model->gender = Yii::$app->user->identity['gender'];
					$Order_model->payment_method = "";
					$Order_model->payment_code = "";
					$Order_model->total = $order_data['total'];
					if(isset($this->comment[$k]) && $this->comment[$k]){
                        $Order_model->comment = $this->comment[$k];
                    }else{
                        $Order_model->comment = $this->comment['merge'];
                    }
					$Order_model->order_status_id = 1;

                    $affiliate_code = "mrhuigou";//默认分销商code为空
					if($affiliate_id = Yii::$app->session->get("from_affiliate_uid",0)){
					    $affiliate = Affiliate::findOne(['affiliate_id'=>$affiliate_id]);
                        if($affiliate && $affiliate->status ){//非智慧青岛
                            $affiliate_code = $affiliate->code;
                            if($affiliate->affiliate_id != 259){
                                $customer = Customer::findOne(['customer_id'=>Yii::$app->user->getId()]);
                                if($customer->affiliate_id == Yii::$app->session->get("from_affiliate_uid")){
                                    $affiliate_id = $customer->affiliate_id;
                                }
                            }else{
                                //z智慧青岛
                                $affiliate_id = $affiliate->affiliate_id;
                            }
                        }
					}
					$Order_model->affiliate_id = $affiliate_id;
					$Order_model->affiliate_code = $affiliate_code;
					$Order_model->commission=$this->getOrderCommission($order_data['total'],$affiliate_id);
					if($affiliate_id==0){
                            if($from_share_user=CustomerFollower::findOne(['follower_id'=>Yii::$app->user->getId()])){
                                $Order_model->source_customer_id=$from_share_user->customer_id;
                            }else{
                                $Order_model->source_customer_id=0;
                            }
                    }


					$Order_model->language_id = 2;
					$Order_model->currency_id = 4;
					$Order_model->currency_code = 'CNY';
					$Order_model->currency_value = 1;
					$Order_model->ip = Yii::$app->request->userIP;
					$Order_model->user_agent = Yii::$app->request->userAgent;
					$Order_model->accept_language = Yii::$app->request->getPreferredLanguage();
					$Order_model->date_added = date("Y-m-d H:i:s", time());
					$Order_model->date_modified = date("Y-m-d H:i:s", time());
					if($this->invoice_id){
					    $invoice = Invoice::findOne(['invoice_id'=>$this->invoice_id]);

                        $Order_model->invoice_temp = $invoice_temp[$invoice->type_invoice];

                        if($invoice->type_invoice == 1 ){
                            $Order_model->invoice_title = $invoice->title_invoice."|".$invoice->code;
                        }else if($invoice->type_invoice == 2){
                            $Order_model->invoice_title = $invoice->title_invoice."|".$invoice->code;
                        }elseif($invoice->type_invoice == 3){
                            $Order_model->invoice_title = $invoice->title_invoice."|".$invoice->code.'| '.$invoice->address_and_phone .'|'.$invoice->bank_and_account;
                        }else{
                            $Order_model->invoice_title = "";
                        }
                    }else{
                        $Order_model->invoice_temp = '不需要发票';
                    }


                    if(Yii::$app->session->get('customer_point_h5') && $order_data['base']->store_id == 1){
                        $Order_model->use_points = "1";
                    }else{
                        $Order_model->use_points = "0";
                    }
					$Order_model->sent_to_erp = "N";
					if (!$Order_model->save(false)) {
						throw new \Exception("订单数据异常");
					}

					//订单地址表数据
					$shipping_address = [];
					if (($this->address_id) && isset($this->delivery[$k]) && ($delivery = $this->delivery[$k])) {
					     if ($address = Address::findOne(['address_id' => $this->address_id, 'customer_id' => Yii::$app->user->getId()])) {
							$address_formart=$address->address_1;
							$shipping_address = [
								'shipping_method' => '每日惠购配送',
								'shipping_code' => 'limit',
								'delivery_code' => 'limit',
								'delivery_date' => $delivery['date'],
								'delivery_time' => $delivery['time'],
								'delivery_station_id' => 0,
								'delivery_station_code' => '',
								'username' => $address->firstname,
								'telephone' => $address->telephone,
								'address' => $address_formart,
								'postcode' => $address->postcode,
								'zone' => $address->zone->name,
								'zone_code' => $address->zone->code,
								'zone_id' => $address->zone_id,
								'city' => $address->citys ? $address->citys->name : "",
								'city_code' => $address->citys ? $address->citys->code : "",
								'city_id' => $address->city_id,
								'district' => $address->district ? $address->district->name : "",
								'district_code' => $address->district ? $address->district->code : "",
								'district_id' => $address->district_id,
								'lat' => $address->lat,
								'lng' => $address->lng,
								'is_delivery' => 1,
							];
						} else {
							throw new \Exception("收货地址不存在");
						}
					}
					if ($shipping_address) {
						$Order_Shipping = new OrderShipping();
						$Order_Shipping->order_id = $Order_model->order_id;
						$Order_Shipping->station_id = $shipping_address['delivery_station_id'];
						$Order_Shipping->station_code = $shipping_address['delivery_station_code'];
						$Order_Shipping->shipping_firstname = $shipping_address['username'];
						$Order_Shipping->shipping_telephone = $shipping_address['telephone'];
						$Order_Shipping->shipping_address_1 = $shipping_address['address'];
						$Order_Shipping->shipping_postcode = $shipping_address['postcode'];
						$Order_Shipping->shipping_zone = $shipping_address['zone'];
						$Order_Shipping->shipping_zone_id = $shipping_address['zone_id'];
						$Order_Shipping->shipping_zone_code = $shipping_address['zone_code'];
						$Order_Shipping->shipping_city = $shipping_address['city'];
						$Order_Shipping->shipping_city_id = $shipping_address['city_id'];
						$Order_Shipping->shipping_city_code = $shipping_address['city_code'];
						$Order_Shipping->shipping_district = $shipping_address['district'];
						$Order_Shipping->shipping_district_id = $shipping_address['district_id'];
						$Order_Shipping->shipping_district_code = $shipping_address['district_code'];
						$Order_Shipping->shipping_method = $shipping_address['shipping_method'];
						$Order_Shipping->shipping_code = $shipping_address['shipping_code'];
						$Order_Shipping->delivery_code = $shipping_address['delivery_code'];
						$Order_Shipping->delivery = $shipping_address['delivery_date'];
						$Order_Shipping->delivery_time = $shipping_address['delivery_time'];
						$Order_Shipping->lat = $shipping_address['lat'];
						$Order_Shipping->lng = $shipping_address['lng'];
						$Order_Shipping->is_delivery = $shipping_address['is_delivery'];
						if (!$Order_Shipping->save(false)) {
							throw new \Exception("订单收货地址异常");
						}

					}
                    $order_products_array = [];
					//添加商品信息
					if (isset($order_data['products']) && $order_data['products']) {
						foreach ($order_data['products'] as $product) {

							if(!$product->product->getStockCount()){
								throw new \Exception("库存不足");
							}
                            $order_products_array[] = $product->product->product_id;
							$product_price = $product->getPrice();
							$product_total = $product->getCost();
							$promotion_id = 0;
							$promotion_detail_id = 0;
							if ($product->promotion) {
								$promotion_id = $product->promotion->promotion_id;
								$promotion_detail_id = $product->promotion->promotion_detail_id;
							}
//							$product_base  = ProductBase::findOne(['product_base_id'=>$product->product->product_base_id]);
//							$product_base->date_modified = date("Y-m-d H:i:s");
//                            $product_base->save();
							$Order_product = new OrderProduct();
							$Order_product->order_id = $Order_model->order_id;
							$Order_product->product_base_id = $product->product->product_base_id;
							$Order_product->product_base_code = $product->product->product_base_code;
							$Order_product->product_id = $product->product->product_id;
							$Order_product->product_code = $product->product->product_code;
							$Order_product->model = $product->product->model;
							$Order_product->name = $product->product->description->name;
							$Order_product->quantity = $product->quantity;
							$Order_product->price = $product_price;
							$Order_product->total = $product_total;
							$Order_product->reward = $product->product->points;
							$Order_product->unit = $product->product->unit;
							$Order_product->format = $product->product->format;
							$Order_product->sku_name = $product->product->getSku();
							$Order_product->pay_total = $this->getOrderProductPayTotal($product->product->product_id, $product_total, $Order_model->store_id);
							$Order_product->refund_qty = 0;
							$Order_product->promotion_id = $promotion_id;
							$Order_product->promotion_detail_id = $promotion_detail_id;
							$Order_product->commission=$product->product->getCommission($Order_model->source_customer_id,$Order_product->pay_total);
							if (!$Order_product->save(false)) {
								throw new \Exception("商品创建失败");
							}
							$this->ProductPromotion($Order_product, $product, $Order_model, $product_stock);
							$product_stock[] = [
								'product_code' => $Order_product->product_code,
								'qty' => $Order_product->quantity
							];
						}
					}
                    if(Yii::$app->session->get('source_from_uid')){

                        if(Yii::$app->session->get('source_from_uid')['value'] != Yii::$app->user->getId()){ //自己不能推荐给自己
                            $current_status = false;
                            $aff_personals = AffiliatePersonal::find()->where(['status'=>1])->andWhere(['and','date_start <"'.$Order_model->date_added.'"','date_end > "'.$Order_model->date_added.'"'])->all();
                            Yii::error('aff_personal'.json_encode($aff_personals));
                            if($aff_personals ){
                                foreach ($aff_personals as $aff_personal){
                                    if($aff_personal->details){
                                        foreach ($aff_personal->details as $detail){
                                            if(!empty($order_products_array)){
                                                $key = array_search($detail->product_id, $order_products_array);
                                                if( $key !==false ){
                                                    //可以提成
                                                    $current_status = true;
                                                }
                                            }

                                        }
                                    }
                                }
                            }
                            //指定商品提成必定包含该字段
                            if($current_status){
                                $Order_model->current_source_customer_id=Yii::$app->session->get('source_from_uid')['value'];
                                $Order_model->save();
                            }
                        }
                    }
					//店铺促销（满赠、满减）
					if (isset($order_data['promotion']) && $order_data['promotion']) {
					    if(is_array($order_data['promotion'])){
                            foreach ($order_data['promotion'] as $v){ // 修改数据方式
                                $this->StorePromotion($v, $Order_model->order_id, $product_stock);
                            }
                        }else{
                            $this->StorePromotion($order_data['promotion'], $Order_model->order_id, $product_stock);
                        }
					}
					//优惠券赠品
					if (isset($order_data['coupon_gift']) && $order_data['coupon_gift']) {
                        if(is_array($order_data['coupon_gift'])){
                            foreach ($order_data['coupon_gift'] as $v){ // 修改数据方式
                                $this->CouponGift($v, $Order_model->order_id, $product_stock);
                            }
                        }else{
                            $this->CouponGift($order_data['coupon_gift'], $Order_model->order_id, $product_stock);
                        }

					}
					//添加订单总计信息
					if ($order_data['totals']) {
						foreach ($order_data['totals'] as $total) {
							$Order_total = new OrderTotal();
							$Order_total->order_id = $Order_model->order_id;
							$Order_total->code = $total['code'];
							$Order_total->title = $total['title'];
							$Order_total->text = '￥' . round($total['value'],2);
							$Order_total->value = $total['value'];
							$Order_total->sort_order = $total['sort_order'];
							$Order_total->code_id = isset($total['code_id']) ? $total['code_id'] : "";
							$Order_total->customer_code_id = isset($total['customer_code_id']) ? $total['customer_code_id'] : "";
							if (!$Order_total->save(false)) {
								throw new \Exception("订单小计异常");
							}


							if ($total['code'] == 'coupon' && isset($total['code_id']) && $total['code_id']) {
								$coupon_history = new CouponHistory();
								$coupon_history->coupon_id = $total['code_id'];
								$coupon_history->customer_id = $Order_model->customer_id;
								$coupon_history->order_id = $Order_model->order_id;
								$coupon_history->amount=$total['value'];
								$coupon_history->date_added = $Order_model->date_added;
								if (!$coupon_history->save(false)) {
									throw new \Exception("订单优惠券异常");
								}
							}
							if (isset($total['customer_code_id']) && $total['customer_code_id']) {
								$customer_coupon = CustomerCoupon::findOne(['customer_coupon_id' => $total['customer_code_id']]);
								if ($customer_coupon) {
									$customer_coupon->is_use = 1;
									$customer_coupon->date_used = $Order_model->date_added;
									if (!$customer_coupon->save(false)) {
										throw new \Exception("用户优惠券更新失败");
									}
								}
							}

						}
					}
					//添加订单历史记录
					$Order_history = new OrderHistory();
					$Order_history->order_id = $Order_model->order_id;
					$Order_history->order_status_id = 1;
					$Order_history->comment = '订单提交成功';
					$Order_history->date_added = date('Y-m-d H:i:s', time());
					if (!$Order_history->save(false)) {
						throw new \Exception("订单记录异常");
					}
					//创建合并订单进行支付
					$merge_order_ids[] = $Order_model->order_id;
					$merge_total = round(bcadd($merge_total, $Order_model->total, 4),2);

//                    $this->notice_points($Order_model);
                    }
				if ($product_stock) {
					foreach ($product_stock as $stock) {

                        $fn = function ($product_code,$qty) use (&$fn){
                            $product = Product::findOne(['product_code'=>$product_code]);
                            if(!$product->bepresell){
                                if ($model = WarehouseStock::findOne(['product_code' => $product_code])) {
                                    try {
                                        $model->tmp_qty = $model->tmp_qty + $qty;
                                        $model->save();
                                    }catch ( StaleObjectException $e){
                                        $fn($product_code,$qty);
                                    }

                                    if(!$product->bepresell && !$product->begift) {
                                        if(($model->quantity-$model->tmp_qty)<0){
                                            throw new \Exception("[".$product->product_code."]库存不足");
                                        }
                                    }
                                }else{
                                    throw new \Exception("[".$product_code."]库存不足");
                                }
                            }


                            //\Yii::$app->log->logger->log("code:".$product_code." ; version:".$model->version,Logger::LEVEL_ERROR);

                        };

                        $fn($stock['product_code'],$stock['qty']);

//						if ($model = WarehouseStock::findOne(['product_code' => $stock['product_code']])) {
//							$model->tmp_qty = $model->tmp_qty + $stock['qty'];
//							$model->save();
//							if(!$model->product->bepresell && !$model->product->begift) {
//								if(($model->quantity-$model->tmp_qty)<0){
//									throw new \Exception("[".$model->product->product_code."]库存不足");
//								}
//							}
//						}
					}
				}

				$model = new OrderMerge();
				$model->merge_code = OrderSn::generateNumber();
				$model->order_ids = implode(',', $merge_order_ids);
				$model->customer_id = Yii::$app->user->getId();
				$model->total = $merge_total;
				$model->status = 0;
				$model->date_added = date("Y-m-d H:i:s");
				$model->date_modified = date("Y-m-d H:i:s");
				if (!$model->save(false)) {
					throw new \Exception(json_encode($model->errors));
				}
				$trade_no = $model->merge_code;

				$transaction->commit();
			} catch (\Exception $e) {
				$transaction->rollBack();
				throw new NotFoundHttpException($e->getMessage().' line:'.$e->getLine().' code:'.$e->getCode());
			}
		}
		return $trade_no;
	}

    private function notice_points($Order_model){  //添加 PointCustomerFlow 并未进行通知，通知应在支付成功时候通知
	    try{
	        if($Order_model && $Order_model->use_points){
                $order_totals = $Order_model->orderTotals;
                foreach ($order_totals as $total){
                    if($total->code == 'points'){
                        $point_customer = PointCustomer::findOne(['point_customer_id'=>$total->customer_code_id]);
                        $use_point_balance = abs($total->value);
                        $use_point = bcdiv($use_point_balance,$point_customer->point->rate,0);

                        $user_points = $point_customer->point->pointByCurl;//获取该用户可用积分
                        if($user_points >= $use_point){ //
                            $point_customer_flow = new PointCustomerFlow();
                            $point_customer_flow->point_customer_id = $total->customer_code_id;
                            $point_customer_flow->customer_id = Yii::$app->user->getId();
                            $point_customer_flow->description = '购物消费';
                            $point_customer_flow->amount = -$use_point;
                            $point_customer_flow->status = 1;//
                            $point_customer_flow->points = 0;//没有实际意义，以后可以删除
                            $point_customer_flow->remark = json_encode(['point_customer_id'=>$point_customer->point_customer_id,'order_id'=>$Order_model->order_id]);
                            $point_customer_flow->date_added = date('Y-m-d H:i:s');
                            $point_customer_flow->type = 'order';
                            $point_customer_flow->type_id = $Order_model->order_id;
                            $point_customer_flow->save(false);
//                            if ($point_customer_flow->save(false)) {
//                                $data['telephone'] = $Order_model->telephone;
//                                $data['changeType'] = 2; //1增加 2扣除
//                                $data['description'] = '订单消费';
//                                $data['orderId'] = $Order_model->order_id;
//                                $data['count'] = 1;
//                                $data['status'] = 1;//扣减积分
//                                $data['creditValue'] = $point_customer_flow->amount;
//                                $data['changeDate'] = date('Y-m-d H:i:s');
//                                $data['changeResource'] = 6;
//                                $data['point_customer_flow_id'] = $point_customer_flow->point_customer_flow_id;
//                                $point_customer->point->notice($data);
//                            }
                        }else{
                            throw new \Exception("积分不足，请重新下单");
                        }
                    }

                }
            }


        }catch (Exception $e){
            throw new \Exception("积分异常:".$e->getMessage());
        }

    }

	protected function StorePromotion($promotion_order, $order_id, &$product_stock)
	{
		if ($promotion_order) {
			//进行订单赠品操作
			if ($promotion_order->behave_gift && $promotion_order->gifts) {
				foreach ($promotion_order->gifts as $gift) {
					//比率 购买数量除以（基数/数量）
					$gift_quantity = $gift->quantity;
					//促销库存限制过滤 INV、QTY
					if ($gift->uplimit_quantity > 0) {
						$count = PromotionHistory::find()->where(['and',
							'promotion_type="OrderGift"', 'status=1',
							'promotion_id=' . $gift->promotion_detail_id,
							'promotion_detail_id=' . $gift->promotion_detail_gift_id,
						])->sum('quantity');
						if ($gift->uplimit_quantity < ($count ? $count + $gift_quantity : $gift_quantity)) {
							continue;
						}
						if ($gift->gift_type == 'COUPON') {
							$order_coupon = new OrderCoupon();
							$order_coupon->coupon_id = $gift->coupon_id;
							$order_coupon->order_id = $order_id;
							$order_coupon->status = 0;
							$order_coupon->date_added = date('Y-m-d H:i:s', time());
							if (!$order_coupon->save(false)) {
								throw new \Exception("订单赠品优惠券创建失败");
							}
						} else {
							if ($gift->product->productBase->begift == 0 && ($gift->product->getStockCount() < $gift_quantity)) {
								continue;
							}
							$Order_gift = new OrderGift();
							$Order_gift->order_id = $order_id;
							$Order_gift->order_product_id = 0;
							$Order_gift->promotion_id = $gift->promotion_detail_id;
							$Order_gift->promotion_detail_id = $gift->promotion_detail_gift_id;
							$Order_gift->product_base_id = $gift->product->product_base_id;
							$Order_gift->product_base_code = $gift->product->product_base_code;
							$Order_gift->product_id = $gift->product->product_id;
							$Order_gift->product_code = $gift->product->product_code;
							$Order_gift->name = $gift->product->product_code;
							$Order_gift->model = $gift->product->model;
							$Order_gift->name = $gift->product->description->name;
							$Order_gift->quantity = $gift_quantity;
							$Order_gift->price = 0;
							$Order_gift->total = 0;
							$Order_gift->tax = 0;
							$Order_gift->unit = $gift->product->unit;
							$Order_gift->format = $gift->product->format;
							if (!$Order_gift->save(false)) {
								throw new \Exception("订单赠品创建失败");
							}
							$product_stock[] = [
								'product_code' => $Order_gift->product_code,
								'qty' => $Order_gift->quantity
							];
						}
						//添加商品赠品促销日志
						$promotion_histroy = new PromotionHistory();
						$promotion_histroy->promotion_type = 'OrderGift';
						$promotion_histroy->promotion_id = $gift->promotion_detail_id;
						$promotion_histroy->promotion_detail_id = $gift->promotion_detail_gift_id;
						$promotion_histroy->order_id = $order_id;
						$promotion_histroy->customer_id = Yii::$app->user->getId();
						$promotion_histroy->quantity = $gift_quantity;
						$promotion_histroy->status = 1;
						$promotion_histroy->date_added = date('Y-m-d H:i:s', time());
						if (!$promotion_histroy->save(false)) {
							throw new \Exception("商品促销赠品日志创建失败");
						}
					}
				}
			}
			//添加订单促销日志
			$promotion_histroy = new PromotionHistory();
			$promotion_histroy->promotion_type = 'ORDER';
			$promotion_histroy->promotion_id = $promotion_order->promotion_id;
			$promotion_histroy->promotion_detail_id = $promotion_order->promotion_order_id;
			$promotion_histroy->order_id = $order_id;
			$promotion_histroy->customer_id = Yii::$app->user->getId();
			$promotion_histroy->quantity = 1;
			$promotion_histroy->status = 1;
			$promotion_histroy->date_added = date('Y-m-d H:i:s', time());
			if (!$promotion_histroy->save(false)) {
				throw new \Exception("订单促销日志创建失败");
			}
		}
	}

	protected function CouponGift($coupon_gifts, $order_id, &$product_stock)
	{
		if ($coupon_gifts) {
			foreach ($coupon_gifts as $gift) {
				$Order_gift = new OrderGift();
				$Order_gift->order_id = $order_id;
				$Order_gift->order_product_id = 0;
				$Order_gift->promotion_id = 0;
				$Order_gift->promotion_detail_id = 0;
				$Order_gift->product_base_id = $gift->product->product_base_id;
				$Order_gift->product_base_code = $gift->product->product_base_code;
				$Order_gift->product_id = $gift->product->product_id;
				$Order_gift->product_code = $gift->product->product_code;
				$Order_gift->name = $gift->product->product_code;
				$Order_gift->model = $gift->product->model;
				$Order_gift->name = $gift->product->description->name;
				$Order_gift->quantity = $gift->qty;
				$Order_gift->price = 0;
				$Order_gift->total = 0;
				$Order_gift->tax = 0;
				$Order_gift->unit = $gift->product->unit;
				$Order_gift->format = $gift->product->format;
				if (!$Order_gift->save(false)) {
					throw new \Exception("订单赠品创建失败");
				}
				$product_stock[] = [
					'product_code' => $gift->product->product_code,
					'qty' => $gift->qty
				];
			}
		}
	}

	protected function ProductPromotion($Order_product, $product, $Order_model, &$product_stock)
	{
		if ($product->promotion) {
			if ($product->promotion->behave_gift && $gifts = $product->promotion->gifts) {
				foreach ($gifts as $gift) {
					if ($gift->base_number <= $product->quantity) {
						if (!$gift->status) {
							continue;
						}
						//比率 购买数量除以（基数/数量）
						if ($gift->type == "PERCENTAGE") {
							$gift_quantity = intval($product->quantity / ($gift->base_number / $gift->quantity));
						} elseif ($gift->type == "MULTIPLE") {
							//倍数
							$gift_quantity = intval($product->quantity / $gift->base_number) * $gift->quantity;
						} else {
							$gift_quantity = $gift->quantity;
						}
						//促销库存限制过滤 INV、QTY
						if ($gift->uplimit_quantity > 0) {
							$count = PromotionHistory::find()->where(['and',
								'promotion_type="ProductGift"', 'status=1',
								'promotion_id=' . $gift->promotion_detail_id,
								'promotion_detail_id=' . $gift->promotion_detail_gift_id,
							])->sum('quantity');
							if ($gift->uplimit_quantity < ($count ? $count + $gift_quantity : $gift_quantity)) {
								continue;
							}
							if ($gift_quantity) {
								if ($gift->gift_type == 'COUPON') {
									$order_coupon = new OrderCoupon();
									$order_coupon->coupon_id = $gift->coupon_id;
									$order_coupon->order_id = $Order_model->order_id;
									$order_coupon->status = 0;
									$order_coupon->date_added = date('Y-m-d H:i:s', time());
									if (!$order_coupon->save(false)) {
										throw new \Exception("订单赠品优惠券创建失败");
									}
								} else {
									if ($gift->product->productBase->begift == 0 && ($gift->product->getStockCount() < $gift_quantity)) {
										continue;
									}
									$Order_gift = new OrderGift();
									$Order_gift->order_id = $Order_model->order_id;
									$Order_gift->order_product_id = $Order_product->order_product_id;
									$Order_gift->product_base_id = $gift->product->product_base_id;
									$Order_gift->product_base_code = $gift->product->product_base_code;
									$Order_gift->product_id = $gift->product->product_id;
									$Order_gift->product_code = $gift->product->product_code;
									$Order_gift->model = $gift->product->model;;
									$Order_gift->name = $gift->product->description->name;
									$Order_gift->quantity = $gift_quantity;
									$Order_gift->price = 0;
									$Order_gift->total = 0;
									$Order_gift->tax = 0;
									$Order_gift->unit = $gift->product->unit;
									$Order_gift->format = $gift->product->format;
									$Order_gift->promotion_id = $gift->promotion_detail_id;
									$Order_gift->promotion_detail_id = $gift->promotion_detail_gift_id;
									if (!$Order_gift->save(false)) {
										throw new \Exception("商品赠品创建失败");
									}
									$product_stock[] = [
										'product_code' => $Order_gift->product_code,
										'qty' => $Order_gift->quantity
									];
								}
								//添加商品赠品促销日志
								$promotion_histroy = new PromotionHistory();
								$promotion_histroy->promotion_type = 'ProductGift';
								$promotion_histroy->promotion_id = $gift->promotion_detail_id;
								$promotion_histroy->promotion_detail_id = $gift->promotion_detail_gift_id;
								$promotion_histroy->order_id = $Order_model->order_id;
								$promotion_histroy->customer_id = Yii::$app->user->getId();
								$promotion_histroy->quantity = $gift_quantity;
								$promotion_histroy->status = 1;
								$promotion_histroy->date_added = date('Y-m-d H:i:s', time());
								if (!$promotion_histroy->save(false)) {
									throw new \Exception("商品促销赠品日志创建失败");
								}
							}
						}
					}
				}
			}
			//添加订单促销日志
			$promotion_histroy = new PromotionHistory();
			$promotion_histroy->promotion_type = 'SINGLE';
			$promotion_histroy->promotion_id = $product->promotion->promotion_id;
			$promotion_histroy->promotion_detail_id = $product->promotion->promotion_detail_id;
			$promotion_histroy->order_id = $Order_model->order_id;
			$promotion_histroy->customer_id = Yii::$app->user->getId();
			$promotion_histroy->quantity = $product->quantity;
			$promotion_histroy->status = 1;
			$promotion_histroy->date_added = date('Y-m-d H:i:s', time());
			if (!$promotion_histroy->save(false)) {
				throw new \Exception("订单商品促销日志创建失败");
			}
		}
	}

	//订单商品进行折扣摊销
	protected function getOrderProductPayTotal($product_id, $product_total, $store_id)
	{
        $order_product_paytotal = $this->order_product_paytotal;
        if (isset($order_product_paytotal[$store_id]) && ($rate_datas = $order_product_paytotal[$store_id])) {
            if (isset($rate_datas[$product_id])) {
                $product_total = $rate_datas[$product_id];
            }
        }
        return $product_total;//返回实付金额

	}
	protected function getOrderCommission($total,$affiliate_id){
		$commission=0;
			if($model=Affiliate::findOne(['affiliate_id'=>$affiliate_id,'status'=>1])){
			    if($model->settle_type == 'order'){
			        if($model->settle_commission == 'P'){
                        $commission = bcmul($model->commission,$total,2);
                    }else{
                        $commission = $model->commission;
                    }
                }elseif($model->settle_type == 'customer'){
                    $order_status_ids = [2,3,5,9,10,11];
                    $order_count = Order::find()->where(['customer_id'=>Yii::$app->user->getId(),'order_status_id'=>$order_status_ids])->andWhere(['and','date_added <"'.date("Y-m-d H:i:s", time()).'"'])->count();
                    if($order_count<1){
                        if($model->settle_commission == 'P'){
                            $commission = bcmul($model->commission,$total,2);
                        }else{
                            $commission = $model->commission;
                        }
                    }
                }
			}
		return $commission;
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
                        $data['productId'] = $order->order_id;
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
}