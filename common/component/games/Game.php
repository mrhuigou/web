<?php
namespace common\component\games;

use api\models\V1\Coupon;
use api\models\V1\CustomerChest;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerReward;
use api\models\V1\GameChance;
use api\models\V1\GameCost;
use api\models\V1\Order;
use api\models\V1\Product;
use Yii;

use yii\helpers\Url;
class Game{
    private $order;
    public function playGame($data){
        $return = array();
        if($data['type'] == 'chance'){ //chance 包括 order login signin;
                $game_chance = GameChance::find()->where(['and','customer_id='.Yii::$app->user->getId(),'status=0','expiration_time >="'.date('Y-m-d H:i:s').'"'])->orderBy('expiration_time asc')->one();
               if(!empty($game_chance)){
                   $game_chance->status = 1;
                   $game_chance->save();
                   if(!empty($game_chance) && $game_chance->from == 'order'){
                       $order = Order::findOne($game_chance->from_id);
                        $this->order = $order;
                       $draw = self::getDraw();
                       if($draw['gift_from'] == 'coupon'){
                           $data['gift_from'] = 'coupon';
                           $data['gift_id'] = $draw['info']['id'];
                           self::addGift($data); //
                           $return['status'] = 'success';
                           $return['message'] = "恭喜您获得家润折扣券：".$draw['info']['name'];
                       }
                       if($draw['gift_from'] == 'product'){
                           $data['gift_from'] = 'product';
                           $data['gift_id'] = $draw['info']['id'];
                           self::addGift($data);
                           $return['status'] = 'success';
                           $return['message'] = "恭喜您获得家润赠品：".$draw['info']['name'];
                       }
                       if($draw['gift_from'] == 'none'){
                           $return['status'] = 'success';
                           $return['message'] = "木有砸出任何东西哎";
                       }
                       return $return;
                   }else{
                       $return['status'] = 'false';
                       $return['message'] = '您已经砸过了，下次再试试吧！';
                       return $return;
                   }
               }else{
                   $return['status'] = 'false';
                   $return['message'] = '您已经砸过了，下次再试试吧！';
                   return $return;
               }


        }elseif($data['type'] == 'point'){
            $customer_points = CustomerReward::find()->where(['customer_id'=>Yii::$app->user->getId()])->sum('points');
            if($customer_points >= $data['points']){
                $save = true;
                if($data['points'] > 0){ //
                    $customer_reward = new CustomerReward();
                    $customer_reward->customer_id = Yii::$app->user->getId();
                    $customer_reward->description = "游戏中奖所赠";
                    $customer_reward->points = -$data['points'];
                    $customer_reward->type_id = 1;
                    $save = $customer_reward->save();
                }

                if($save){
                    //data['gfit_from'] 只能是 coupon
                    $draw = self::getDraw();
                    if($draw['gift_from'] == 'coupon'){
                        $data['gift_from'] = 'coupon';
                        $data['gift_id'] = $draw['info']['id'];
                        self::addGift($data); //
                        $return['status'] = 'success';
                        $return['message'] = "恭喜您获得：".$draw['info']['name'];
                    }
                    if($draw['gift_from'] == 'product'){
                        $data['gift_from'] = 'product';
                        $data['gift_id'] = $draw['info']['id'];
                        self::addGift($data);
                        $return['status'] = 'success';
                        $return['message'] = "恭喜您获得：".$draw['info']['name'];
                    }
                    if($draw['gift_from'] == 'none'){
                        $return['status'] = 'success';
                        $return['message'] = "木有砸出任何东西哎";
                    }
                    return $return;
                }
            }else{
                $return['status'] = 'false';
                $return['message'] = '积分不足';
                return $return;
            }
        }elseif($data['type']=='hongbao'){
            $game_chance = GameChance::find()->where(['and','customer_id='.Yii::$app->user->getId(),'status=0','expiration_time >="'.date('Y-m-d H:i:s').'"'])->andWhere(['from'=>'Hongbao'])->orderBy('expiration_time asc')->one();
            if(!empty($game_chance)){
                $game_chance->status = 1;
                $game_chance->save();
                $coupon =$this->creatHongbao();
                $customer_coupon = new CustomerCoupon();
                $customer_coupon->customer_id = Yii::$app->user->getId();
                $customer_coupon->coupon_id = $coupon->coupon_id;
                $customer_coupon->description = '家润红包';
                $customer_coupon->is_use = 0;
                $customer_coupon->date_added = date('Y-m-d H:i:s');
                $customer_coupon->save();
                $return['status'] = 'success';
                $return['message'] = "恭喜您获得：".$coupon->name;
                return $return;
            }else{
                $return['status'] = 'false';
                $return['message'] = '您的机会已经用光了';
                return $return;
            }
        }
    }
    private function addGift($data,$order=array()){
        //添加奖品到 折扣券或者宝箱
        if($data['gift_from'] == 'product'){ //宝箱
            $gift = Product::findOne($data['gift_id']);
            $customer_chest = new CustomerChest();
            $customer_chest->customer_treasure_id = 26;
            $customer_chest->customer_id = Yii::$app->user->getId();
            $customer_chest->product_id = $gift->product_id;
            $customer_chest->product_code = $gift->product_code;
            $customer_chest->product_type = 1;
            $customer_chest->status = 0;
            $customer_chest->date_added = date('Y-m-d H:i:s');
            $customer_chest->order_id = $order->order_id;
            $customer_chest->order_no = $order->order_no;
            $customer_chest->date_expired = $data['date_expired'];
            $customer_chest->is_online = 1;
            $customer_chest->save();
        }else if($data['gift_from'] == 'coupon'){
            $coupon = Coupon::findOne($data['gift_id']);
            $customer_coupon = new CustomerCoupon();
            $customer_coupon->customer_id = Yii::$app->user->getId();
            $customer_coupon->coupon_id = $coupon->coupon_id;
            $customer_coupon->description = '游戏获赠';
            $customer_coupon->is_use = 0;
            $customer_coupon->date_added = date('Y-m-d H:i:s');
            $customer_coupon->save();
        }
    }
    // @param gift 如果为空则最终奖品去 getGiftList中去取；
    public function getDraw($gift = array()){
            $surprised_arr = array(
                '0' => array('product_id'=>0,'name'=>'未中奖','v'=>0),
                '1' => array('product_id'=>1,'name'=>'中奖','v'=>100),
            );
            foreach ($surprised_arr as $key => $val) {
                $surp_arr[$val['product_id']] = $val['v'];
            }

            $surprised_id = self::getRand($surp_arr);

            if($surprised_id == '1'){
                if(empty($gift)){
                    $gift = self::createCoupon($this->order);
                    $res['gift_from'] = 'coupon';
                    $res['info']['id'] = $gift->coupon_id;
                    $res['info']['name'] = $gift->name;
                }else{
                    $res['gift_from'] = 'coupon';
                    $res['info']['id'] = $gift->coupon_id;
                    $res['info']['name'] = $gift->name;
                }

                return $res;
            }else{
                $res['gift_from'] = 'none';
                return $res;
            }

        //计算概率
    }
    public  function getRand($proArr) {
        $result = '';

        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);

        return $result;
    }
    private function getGiftList(){
        $coupon_id = '103';
        $coupon = Coupon::findOne($coupon_id);
        $gift_list = array();
        $gift_list[] = $coupon;
        return $gift_list;

    }
    private function createCoupon($order){
        $rate = 0.02;
        $low_rate = 0.01;
        $discount = 0;
        if($order->total > 1){
            $discount = floatval(number_format(rand($order->total * $low_rate *100,$order->total * $rate *100)/100,1));
        }elseif($order->total ==1){
            $discount = floatval(number_format(rand(0.8 * 100,1.7 * 100)/100,1));
        }
        if($discount < 0.1){
            $discount = 0.1;
        }

        $new_coupon = new Coupon();
        $new_coupon->name = $discount.'元购物券';
        $new_coupon->code = 'game'.rand(10000,99999);
        $new_coupon->type = 'F';
        $new_coupon->discount = $discount;
        $new_coupon->logged = 1;
        $new_coupon->shipping = 0;
        $new_coupon->total = $discount;
        $new_coupon->date_start = date('Y-m-d H:i:s');
        $new_coupon->date_end = date('Y-m-d H:i:s',strtotime('+30 day'));
        $new_coupon->uses_total = 1;
        $new_coupon->uses_customer = "1";
        $new_coupon->is_open = 0;
        $new_coupon->status = 1;
        $new_coupon->date_added = date('Y-m-d H:i:s');
        $new_coupon->date_added = $order->store_id;
        $new_coupon->save();
        return $new_coupon;
    }
    public function creatHongbao(){
        $discount=bcdiv(rand(10,29),10,1);
        $new_coupon = new Coupon();
        $new_coupon->name = $discount.'元红包';
        $new_coupon->code = 'HongBao'.time().rand(10000,99999);
        $new_coupon->type = 'F';
        $new_coupon->discount = $discount;
        $new_coupon->logged = 1;
        $new_coupon->shipping = 0;
        $new_coupon->total = $discount;
        $new_coupon->date_start = date('Y-m-d H:i:s');
        $new_coupon->date_end = date('Y-m-d H:i:s',strtotime('+7 day'));
        $new_coupon->uses_total = 1;
        $new_coupon->uses_customer = "1";
        $new_coupon->is_open = 0;
        $new_coupon->status = 1;
        $new_coupon->date_added = date('Y-m-d H:i:s');
        $new_coupon->save();
        return $new_coupon;
    }
}