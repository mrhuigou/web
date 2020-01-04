<?php
/**
 * Created by PhpStorm.
 * User: 22095
 * Date: 2020/1/3
 * Time: 17:25
 */

namespace h5\models;


use yii\base\Model;
use api\models\V1\Address;
use common\models\User;
use yii\web\NotFoundHttpException;
use Yii;
use api\models\V1\Order;
use common\component\Helper\OrderSn;
use api\models\V1\Affiliate;
use api\models\V1\AffiliatePersonal;
use api\models\V1\CouponHistory;
use api\models\V1\Customer;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerFollower;
use api\models\V1\Invoice;
use api\models\V1\OrderHistory;
use api\models\V1\OrderMerge;
use api\models\V1\OrderProduct;
use api\models\V1\OrderShipping;
use api\models\V1\OrderTotal;
use api\models\V1\Product;
use api\models\V1\WarehouseStock;
use yii\db\StaleObjectException;

class ViewDeliveryForm extends  Model
{
    public $username;
    public $telephone;
    public $poiname;
    public $poiaddress;
    public $address;
    public $postcode = 266000;
    public $lat;
    public $lng;
    public $is_default = 1;
    public $address_id = 0;
    public $has_other_zone;
    public $province;
    public $city;
    public $district;
    public $in_range = 1; //1在服务范围内，只能选市内四区；0可能超出服务范围
    public $total;
    public $limit_min_quantity;
    public $coupon_id;
    public $delivery;
    public $cart_data;
    public $product_id;
    public function __construct($config = [])
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


        $this->telephone=Yii::$app->user->identity->telephone;
        if ($model = Address::findOne(['address_id' => $this->address_id, 'customer_id' => Yii::$app->user->getId()])) {
            $this->address_id = $model->address_id;
            $this->username = $model->firstname;
            $this->telephone = $model->telephone;
            $this->postcode = $model->postcode;
            $this->poiname = $model->poiname;
            $this->poiaddress=$model->poiaddress;
//			if($model->poiname && strpos($model->address_1,$model->poiname) !== false){
//				$this->address =$model->address_1;
//			}else{
//				$this->address = $model->poiaddress.$this->poiname.$model->address_1;
//			}
            $this->address =$model->address_1;
            $this->province=$model->zone?$model->zone->name:"山东省";
            $this->city=$model->citys?$model->citys->name:"青岛市";
            $this->district=$model->district?$model->district->name:"请选择";
            $this->lat = $model->lat;
            $this->lng = $model->lng;
        }else{
            $this->province="山东省";
            $this->city="青岛市";
            $this->district="请选择";
        }
        $this->has_other_zone=false;

        //商品id

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['province','city','district','address','username','telephone','is_default'], 'required'],
            [['province','city','district','address','username','telephone','is_default'], 'safe'],
            [['address_id', 'delivery'], 'safe'],
//            [['address_id', 'delivery'], 'FormValidate'],
            //[['invoice_type'],'ValidateInvoice']
        ];
    }

    public function attributeLabels()
    {
        return [
            'address' => '详细地址',
            'username' => '收货人',
            'telephone'=>'手机号',
        ];
    }

    public function submit()
    {   //交易号
        $trade_no = "";
//		print_r($this->order_coupon_product_rate);
//		print_r($this->order_product_rate);exit;
        if ($this->validate()) {
            $merge_order_ids = [];
            $merge_total = 0;
            $product_stock = [];
            $transaction = \Yii::$app->db->beginTransaction();
            try {

                foreach ($this->cart_data as $k => $order_data) {
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
                    $Order_model->comment = $this->comment[$k];
                    $Order_model->order_status_id = 1;

                    if($affiliate_id = Yii::$app->session->get("from_affiliate_uid",0)){
                        $affiliate = Affiliate::findOne(['affiliate_id'=>$affiliate_id]);
                        if($affiliate && $affiliate->status ){//非智慧青岛
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
                        $this->StorePromotion($order_data['promotion'], $Order_model->order_id, $product_stock);
                    }
                    //优惠券赠品
                    if (isset($order_data['coupon_gift']) && $order_data['coupon_gift']) {
                        $this->CouponGift($order_data['coupon_gift'], $Order_model->order_id, $product_stock);
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

                    $this->notice_points($Order_model);
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
}