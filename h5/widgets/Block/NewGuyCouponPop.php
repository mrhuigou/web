<?php

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
namespace h5\widgets\Block;

use api\models\V1\CouponRules;
use api\models\V1\CustomerCoupon;
use api\models\V1\Order;
use api\models\V1\PrizeBox;
use h5\controllers\CouponController;
use yii\bootstrap\Widget;
use  Yii;

class NewGuyCouponPop extends Widget {

    public function init()
    {
        parent::init();
    }

    public function run()
    {

        if (!\Yii::$app->user->isGuest) {
            if (!$user_status = $this->valiated(Yii::$app->user->getId())) {
                //新手券实效了，但还未下过单
                //重新发放一张新手券
                $customer_coupons = $this->sendGift(Yii::$app->user->getId());
                if($customer_coupons){
                    $result['send_status'] = true;
                    $result['message'] = '发放成功';
                    $result['status'] = true;
//                    $result['coustomer_coupon']['discount'] = $customer_coupon->coupon->discount;
//                    $result['coustomer_coupon']['total'] = $customer_coupon->coupon->total;
//                    $result['coustomer_coupon']['end_time'] = $customer_coupon->end_time;
//                    $result['end_time'] = $customer_coupon->end_time;
                    $totals = 0;
                    foreach ($customer_coupons as $customer_coupon){
                        $totals = $totals + $customer_coupon->coupon->discount;
                    }
                    $totals = floor($totals);
                    return $this->render('new-guy-coupon-pop',['totals'=>$totals,'customer_coupons'=>$customer_coupons]);
                }
            }
        }

    }

    private function sendGift($customer_id)
    {
        $customer_coupons = [];
        if ($gifts = PrizeBox::find()->where(['status' => 1, 'type' => 'register'])->all()) {
            if($gifts){
                foreach ($gifts as $gift){
                    if (!$user_status = $this->getUserStatus($customer_id, $gift->coupon_id)) {
                        $customer_coupon = new CustomerCoupon();
                        $customer_coupon->customer_id = $customer_id;
                        $customer_coupon->coupon_id = $gift->coupon_id;
                        $customer_coupon->description = "注册礼券";
                        $customer_coupon->is_use = 0;
                        if ($gift->coupon->date_type == 'DAYS') {
                            $customer_coupon->start_time = date('Y-m-d H:i:s', time());
                            $customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $gift->coupon->expire_seconds);
                        } else {
                            $customer_coupon->start_time = $gift->coupon->date_start;
                            $customer_coupon->end_time = $gift->coupon->date_end;
                        }
                        $customer_coupon->date_added = date('Y-m-d H:i:s', time());
                        $customer_coupon->save();

                        $customer_coupons[] = $customer_coupon;
                    }
                }
                //return $customer_coupon;
            }
        }
        return $customer_coupons;
    }
    private function valiated($customer_id){
        $status = 0;
//        if ($gifts = PrizeBox::find()->where(['status' => 1, 'type' => 'register'])->all()) {
//            if ($gifts) {
//                foreach ($gifts as $gift) {
//                    $status = $this->getUserStatus($customer_id,$gift->coupon_id);
//                    if($status == 1){
//                        break;
//                    }
//                }
//            }
//
//            if ($user_order = Order::find()->where(['customer_id' => $customer_id])->andWhere(["or", "order_status_id=2", "sent_to_erp='Y'"])->count("order_id")) {
//                //有成功的订单 则不再发放新手券
//                $status = 1;
//            }
//
//        }
        if($customer_id == 17412){
            return 0;
        }
        if ($user_order = Order::find()->where(['and','customer_id='.$customer_id,'order_type_code <> "GroundPush"',['or',"order_status_id=2", "sent_to_erp='Y'"] ])->count("order_id")) {
            //有成功的订单 则不再发放新手券
            $status = 1;
        }
        return $status;
    }
    private function getUserStatus($customer_id, $coupon_id)
    {
        $status = 0;//该用户没有 该coupon

        if ($user_coupon = CustomerCoupon::find()->where(['customer_id' => $customer_id, 'coupon_id' => $coupon_id])->all()) {
            foreach ($user_coupon as $coupon) {
                if ($coupon->is_use == 0 && strtotime($coupon->end_time) < time()) {
                    //已经失效且还未使用  应重新发放新手券

                }else{
                    $status = 1;
                    break;
                }
            }
        }

        return $status;
    }
    //已经不用改方法，继续用PrizeBox
    private function couponRulesStatus($coupon_rules_id){
        $coupon_rules  =  CouponRules::findOne(['coupon_rules_id'=>$coupon_rules_id]);
        $count = 0;
        $status = false;
        if($coupon_rules){
            if($coupon_rules->details){
                foreach ($coupon_rules->details as $detail){
                    //Yii::error(' couponRulesStatus_count:'.$count.';coupon_id:'.$detail->coupon_id);
                    $count = $count + $this->getUserCount(Yii::$app->user->getId(),$detail->coupon_id);
                }
            }
//        Yii::error(' couponRulesStatus——count_sum:'.$count);
            if($count < $coupon_rules->user_total_limit){
                //满足coupont_rules，可以领取
                $status = true;
            }
        }

        return $status;
    }
    private function getUserCount($customer_id, $coupon_id)
    {
        $count = 0;//该用户没有 该coupon

        if ($user_coupon = CustomerCoupon::find()->where(['customer_id' => $customer_id, 'coupon_id' => $coupon_id])->all()) {
            foreach ($user_coupon as $coupon) {
                if ( strtotime($coupon->end_time) > time()) { //未失效的
                    $count = $count + 1;
                }else{
                    //失效的
                    if($coupon->is_use == 1){
                        //且已经使用的
                        $count = $count + 1;
                    }
                }
            }
        }
//        Yii::error(' getUserCount:'.$count);
        return $count;
    }
}