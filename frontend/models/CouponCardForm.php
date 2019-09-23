<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/30
 * Time: 16:12
 */
namespace frontend\models;
use api\models\V1\CouponCard;
use api\models\V1\CouponCardHistory;
use api\models\V1\CustomerCoupon;
use yii\base\Model;
use Yii;
class CouponCardForm extends Model{
    public $card_code;
    public $card_pwd;
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['card_code','card_pwd'],"required"],
            [['card_code','card_pwd'], 'string'],
            ['card_pwd', 'checkCode'],
        ];
    }
    public function checkCode($attribute, $params){
        if($coupon=CouponCard::findOne(['card_code'=>$this->card_code,'card_pwd'=>$this->card_pwd,'status'=>1])){
	        if(($coupon->coupon->date_type=="TIME_SLOT" && strtotime($coupon->coupon->date_end)<=time()) || $coupon->coupon->status==0 ){
		        $this->addError($attribute,'此卡号已过期!');
	        }
	        if($coupon->history){
		        $this->addError($attribute,'此卡号已被激活!');
	        }
        }else{
            $this->addError($attribute,'卡号或密码不正确!');
        }
    }

    public function save(){
        if($this->validate()){
            $card=CouponCard::findOne(['card_code'=>$this->card_code]);
            $model=new CouponCardHistory();
            $model->coupon_card_id=$card->id;
            $model->customer_id=Yii::$app->user->getId();
            $model->date_added=date('Y-m-d H:i:s',time());
            $model->source_from='PC';
            $model->save();
            $customer_coupon=new CustomerCoupon();
            $customer_coupon->customer_id=Yii::$app->user->getId();
            $customer_coupon->coupon_id=$card->coupon_id;
	        $customer_coupon->description=$card->coupon->name;
            $customer_coupon->is_use=0;
	        if ($card->coupon->date_type == 'DAYS') {
		        $customer_coupon->start_time = date('Y-m-d H:i:s', time());
		        $customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $card->coupon->expire_seconds);
	        } else {
		        $customer_coupon->start_time = $card->coupon->date_start;
		        $customer_coupon->end_time = $card->coupon->date_end;
	        }
            $customer_coupon->date_added=date('Y-m-d H:i:s',time());
            $customer_coupon->save();
            return $customer_coupon;
        }
        return null;
    }

    public function attributeLabels()
    {
        return [
            'card_code' => '卡号',
            'card_pwd'=>'密码'
        ];
    }

}