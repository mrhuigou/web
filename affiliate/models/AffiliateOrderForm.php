<?php
namespace affiliate\models;
use api\models\V1\Address;
use api\models\V1\Affiliate;
use api\models\V1\Invoice;
use api\models\V1\Order;
use api\models\V1\User;
use Yii;
class AffiliateOrderForm extends Affiliate {
    public $distribution_type = 1; //配送方式默认为2
    public $address_id;
    public $province;
    public $city;
    public $district;
    public $address = [];
    public function __construct($affiliate_id=0,$fx_user_info)
    {
        //获取自己默认的收货地址
        if (\Yii::$app->session->get('checkout_address_id')) {
            $this->address_id = \Yii::$app->session->get('checkout_address_id');
        }else{

        }

        $affiliate_info = Affiliate::find()->where(['status'=>1,'affiliate_id'=>$affiliate_id])->one();
        if($affiliate_info->mode == 'DOWN_LINE'){//线下
            $this->distribution_type = 2;
        }
        if($this->distribution_type == 2){ //获取团长的配送地址
//            $address['zone_id'] = $affiliate_info->zone_id;
//            $address['city_id'] = $affiliate_info->city_id;
//            $address['district_id'] = $affiliate_info->district_id;
            $this->address['zone'] = $affiliate_info->zone_name;
            $this->address['city'] = $affiliate_info->city_name;
            $this->address['district'] = $affiliate_info->district_name;
            $this->address['address_1'] = $affiliate_info->address;
            $this->address['address_username'] = $fx_user_info['firstname']?:"hdogh";
            $this->address['address_telephone'] = $fx_user_info['telephone'];
        }
        //获取最后下单的地址
        $last_order_info = Order::find()->where(['customer_id'=> $fx_user_info['customer_id'],'sent_to_erp'=> 'Y'])->orderBy('date_added desc')->one();
        if($last_order_info){
            $this->address['zone'] = $last_order_info->orderShipping->shipping_zone;
            $this->address['city'] = $last_order_info->orderShipping->shipping_city;
            $this->address['district'] = $last_order_info->orderShipping->shipping_district;
            $this->address['address_1'] = $last_order_info->orderShipping->shipping_address_1;
            $this->address['address_username'] = $last_order_info->orderShipping->shipping_firstname;
            $this->address['address_telephone'] = $last_order_info->orderShipping->shipping_telephone;
        }
        parent::__construct();
    }

}