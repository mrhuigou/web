<?php

namespace api\models\V1;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $order_id
 * @property string $order_type_code
 * @property string $order_no
 * @property integer $invoice_no
 * @property string $invoice_prefix
 * @property integer $platform_id
 * @property string $platform_name
 * @property string $platform_url
 * @property integer $store_id
 * @property string $store_name
 * @property string $store_url
 * @property integer $customer_id
 * @property integer $customer_group_id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $telephone
 * @property string $gender
 * @property string $merge_code
 * @property string $payment_deal_no
 * @property string $payment_method
 * @property string $payment_code
 * @property string $total
 * @property string $comment
 * @property integer $order_status_id
 * @property integer $affiliate_id
 * @property string $commission
 * @property integer $language_id
 * @property integer $currency_id
 * @property string $currency_code
 * @property string $currency_value
 * @property string $ip
 * @property string $user_agent
 * @property string $accept_language
 * @property string $date_added
 * @property string $date_modified
 * @property string $invoice_temp
 * @property string $invoice_title
 * @property string $trade_account
 * @property string $use_date
 * @property string $time_range
 * @property string $use_nums
 * @property string $use_code
 * @property string $delivery_type
 * @property integer $in_cod
 * @property string $sent_to_erp
 * @property string $sent_time
 * @property integer $parent_id
 * @property string $cycle_store_id
 * @property integer $cycle_id
 * @property integer $periods
 * @property integer $black_score
 * @property integer $source_customer_id
 * @property integer $use_points
 * @property integer $current_source_customer_id
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_no', 'customer_id'], 'required'],
            [['invoice_no', 'platform_id', 'store_id', 'customer_id','black_score', 'customer_group_id', 'order_status_id', 'affiliate_id', 'language_id', 'currency_id', 'in_cod', 'parent_id', 'cycle_store_id', 'cycle_id', 'periods','source_customer_id','use_points'], 'integer'],
            [['total', 'commission', 'currency_value'], 'number'],
            [['comment', 'sent_to_erp','invoice_title'], 'string'],
            [['date_added', 'date_modified', 'use_date', 'sent_time'], 'safe'],
            [['order_type_code', 'platform_url', 'payment_deal_no', 'user_agent', 'accept_language', 'invoice_temp'], 'string', 'max' => 255],
            [['order_no'], 'string', 'max' => 18],
            [['invoice_prefix'], 'string', 'max' => 26],
            [['platform_name'], 'string', 'max' => 64],
            [['store_name', 'merge_code', 'ip', 'trade_account', 'use_nums', 'use_code'], 'string', 'max' => 40],
            [['store_url'], 'string', 'max' => 225],
            [['firstname', 'lastname', 'telephone', 'gender'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 96],
            [['payment_method', 'payment_code'], 'string', 'max' => 128],
            [['currency_code'], 'string', 'max' => 3],
            [['time_range'], 'string', 'max' => 50],
            [['delivery_type'], 'string', 'max' => 10],
            [['order_no'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单 ID',
            'black_score'=>'可疑性',
            'order_type_code' => '类型',
            'order_no' => '订单编号',
            'platform_id' => 'Platform ID',
            'platform_name' => '平台名称',
            'platform_url' => '平台网址',
            'store_id' => '店铺ID',
            'store_name' => '店铺名称',
            'store_url' => '店铺地址',
            'customer_id' => '用户ID',
            'customer_group_id' => 'Customer Group ID',
            'firstname' => '姓名',
            'email' => 'Email',
            'telephone' => '电话',
            'gender' => '性别',
            'merge_code' => '合并付款交易编码号',
            'payment_deal_no' => '交易流水',
            'payment_method' => '付款方式',
            'payment_code' => '付款编码',
            'total' => '订单总额',
            'comment' => '备注',
            'order_status_id' => '订单状态',
            'affiliate_id' => 'Affiliate ID',
            'commission' => '佣金',
            'ip' => 'Ip',
            'user_agent' => 'User Agent',
            'accept_language' => 'Accept Language',
            'date_added' => '创建时间',
            'date_modified' => '更新时间',
            'invoice_temp' => '发票类型',
            'invoice_title' => '发票抬头',
            'sent_to_erp' => '同步',
            'sent_time' => '同步时间',
	        'source_customer_id'=>'来源用户ID'
        ];
    }
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'order_id']);
    }
    public function getOrderProductQty(){
        $qty=0;
        if($this->orderProducts){
            foreach($this->orderProducts as $product){
                $qty+=$product->quantity;
            }
        }
        return $qty;
    }
    public function getOrderRefundQty(){
        $qty=0;
        if($this->orderProducts){
            foreach($this->orderProducts as $product){
                $qty+=$product->getRefundQty();
            }
        }
        return $qty;
    }
    public function getOrderGiftQty(){
        $qty=0;
        if($this->orderGifts){
            foreach($this->orderGifts as $product){
                $qty+=$product->quantity;
            }
        }
        return $qty;
    }
    public function getOrderGiftRefundQty(){
        $qty=0;
        if($this->orderGifts){
            foreach($this->orderGifts as $product){
                $qty+=$product->getRefundQty();
            }
        }
        return $qty;
    }
    public function getOrderGifts(){
        return $this->hasMany(OrderGift::className(), ['order_id' => 'order_id'])->andWhere(['order_product_id'=>0]);
    }
    public function getAllOrderGifts(){
        return $this->hasMany(OrderGift::className(), ['order_id' => 'order_id']);
    }
    public function getOrderStatus(){
        return $this->hasOne(OrderStatus::className(),['order_status_id'=>'order_status_id']);
    }
    public function getOrderShipping(){
        return $this->hasOne(OrderShipping::className(),['order_id'=>'order_id']);
    }
    public function getOrderTotals()
    {
        return $this->hasMany(OrderTotal::className(), ['order_id' => 'order_id']);
    }
    public function getOrderPayments(){
        return $this->hasMany(OrderPayment::className(), ['order_id' => 'order_id']);
    }
    public function getOrderDigitalProducts(){
        return $this->hasMany(OrderDigitalProduct::className(), ['order_id' => 'order_id']);
    }
    public function getOrderDigitalProduct(){
        return $this->hasOne(OrderDigitalProduct::className(), ['order_id' => 'order_id']);
    }
    public function getClubActivityUser(){
        return $this->hasOne(ClubActivityUser::className(), ['order_id' => 'order_id']);
    }
    public function getOrderScan(){
        return OrderScan::findOne(['order_id'=>$this->order_id,'from_table'=>'ORDER']);
    }
    public function getActivity(){
        return $this->hasOne(OrderActivity::className(),['order_id' => 'order_id']);
    }
    public function getOrderDelieryComment(){
	    return $this->hasOne(OrderDeliveryComment::className(),['order_id' => 'order_id']);
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
    public function getStore(){
        return $this->hasOne(Store::className(),['store_id'=>'store_id']);
    }
    public function getPlatform(){
        return $this->hasOne(Platform::className(),['platform_id'=>'platform_id']);
    }
    public function getCommissionCash(){
	    $commission=0;
	    if($order_products=OrderProduct::find()->where(['order_id'=>$this->order_id])->all()){
		    $sub_query=ReturnBase::find()->select('return_id')->where(['order_id'=>$this->order_id,'return_status_id'=>['1','2','3','4','5']]);
	    	foreach ($order_products as $op){
			    $sub_commission=ReturnProduct::find()->where(['return_id'=>$sub_query,'from_table'=>'order_product','from_id'=>$op->order_product_id])->sum('commission');
			    if($sub_commission){
				    $commission=$sub_commission?max(0,$op->commission-$sub_commission):0;
			    }else{
				    $commission+=$op->commission;
			    }
		    }
	    }
	    return $commission;
    }
    public function getAffiliate(){
        return $this->hasOne(Affiliate::className(),['affiliate_id'=>'affiliate_id']);
    }
    public function getOrderCommision($customer_id){
        $result['aff_personal_commision'] = 0;
        $result['aff_customer_commision'] = 0;
        $result['aff_affiliate_commision'] = 0;
        if($this->current_source_customer_id == $customer_id){
            $data_left = $this->getAffPersonalCommission();
            $result['aff_personal_commision'] = $data_left['aff_personal_commision'];
        }

        if ($this->source_customer_id == $customer_id) {
            $commissioin = 0;
            if(isset($data_left)){
                $left_count = count($data_left['left_order_products_array']);
                if( $left_count> 0){
                    if($left_count < count($this->orderProducts) ){
                        $commissioin = $this->getAffCustomerCommision($data_left['left_order_products_array']);
                    }
                }
            }else{
                $commissioin = $this->getAffCustomerCommision();
            }
            if($commissioin > 0){
                $result['aff_customer_commision'] = $commissioin;
            }
        }

        if($this->affiliate_id == $customer_id){
            $commissioin = $this->getAffCustomerCommision();
            if($commissioin > 0){
                $result['aff_affiliate_commision'] = $commissioin;
            }
        }
        return $result;
    }
    public function getAffPersonalCommission(){
        //AffiliatePersonal分销商品提成
        $commission = 0;
        $in_order_products_array = [];
        $order_products_array = OrderProduct::find()->where(['order_id'=>$this->order_id])->asArray()->all();

        if($order_products_array){
            $returnBase = ReturnBase::find()->select('return_id')->where(['order_id'=>$this->order_id,'return_status_id'=>['1','2','3','4','5']]);
            $return_products_array = ReturnProduct::find()->where(['return_id'=>$returnBase])->asArray()->all();
            foreach ($order_products_array as $key => $value){
                if(!empty($return_products_array)){
                    $tmp_key = array_search($value['product_id'], array_column($return_products_array, 'product_id'));
                    if( $tmp_key !==false ){ //已经退货的商品需要从数组中删除
                        unset($order_products_array[$key]);
                    }
                }
            }
        }
        $order_products_array = array_values($order_products_array);
        $aff_personals = AffiliatePersonal::find()->where(['status'=>1])->andWhere(['and','date_start <"'.$this->date_added.'"','date_end > "'.$this->date_added.'"'])->all();
        if($aff_personals ){
            foreach ($aff_personals as $aff_personal){
                if($aff_personal->details){
                    foreach ($aff_personal->details as $detail){
                        if(!empty($order_products_array)){
                            $order_products_array = array_values($order_products_array);
                            $key = array_search($detail->product_id, array_column($order_products_array, 'product_id'));
                            if( $key !==false ){
                                //删除该orderProduct 并记录Personal里提成值
                                $commission += $detail->commissionTotal * $order_products_array[$key]['quantity'];
                                //删除该orderProduct
                                $in_order_products_array[] = $detail->product_id;
                                unset($order_products_array[$key]);
                            }
                        }

                    }
                }
            }
        }
        $data['left_order_products_array'] = $order_products_array;//下一轮普通合伙人提成提成
        $data['in_order_products_array'] = $in_order_products_array;//AffiliatePersonal提成商品
        $data['aff_personal_commision'] = $commission;//AffiliatePersonal提成佣金
        return $data;
    }
    public function getAffCustomerCommision($order_products_array=[]){
        //粉丝订单提成
        $commission = 0;
        if($order_products_array){
            $order_products=OrderProduct::find()->where(['order_id'=>$this->order_id,'product_id'=> array_column($order_products_array, 'product_id')])->all();
        }else{
            $order_products=OrderProduct::find()->where(['order_id'=>$this->order_id])->all();
        }
        if($order_products){
            $sub_query=ReturnBase::find()->select('return_id')->where(['order_id'=>$this->order_id,'return_status_id'=>['1','2','3','4','5']]);
            foreach ($order_products as $op){

                $sub_commission=ReturnProduct::find()->where(['return_id'=>$sub_query,'from_table'=>'order_product','from_id'=>$op->order_product_id])->sum('commission');
                if($sub_commission){
//                    $commission=$sub_commission?max(0,$op->commission-$sub_commission):0;
                    $pro_commission=$sub_commission?max(0,$op->commission-$sub_commission):0;
                }else{
//                    $commission+=$op->commission;
                    $pro_commission = $op->commission;
                }
                $commission+=$pro_commission;
            }
        }
        return $commission;
    }

}
