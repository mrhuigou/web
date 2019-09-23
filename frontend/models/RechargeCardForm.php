<?php
namespace frontend\models;

use api\models\V1\Coupon;
use api\models\V1\CustomerCoupon;
use Yii;
use yii\base\Model;
use common\models\User;
use api\models\V1\RechargeCard;
use api\models\V1\RechargeHistory;
use api\models\V1\CustomerTransaction;

/**
 * Login form
 */
class RechargeCardForm extends Model
{
    public $card_pin;
    public $card_no;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_no'], 'required'],
            [['card_pin'], 'required'],
            [['card_pin'], 'trim'],
            ['card_pin', 'string', 'length' => 16],
            ['card_pin','checkcode'],
        ];
    }
    public function checkcode($attribute, $params){
        if($card = RechargeCard::findOne(['card_pin'=>$this->card_pin,'card_no'=>$this->card_no,'status'=>0])) {
            if ($card->start_time > date("Y-m-d H:i:s")) {
                $this->addError($attribute,'您的充值码尚未生效，请在有效期内激活。');
            }elseif ($card->end_time < date("Y-m-d H:i:s")) {
                $this->addError($attribute,'您的充值码已过期。');
            }
        }else{
            $this->addError($attribute,'您的充值码无效。');
        }
    }

    public function attributeLabels(){
        return ['card_pin'=>'充值码','card_no'=>'充值卡号'];
    }

    public function save()
    {
        if($this->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($card = RechargeCard::findOne(['card_pin' => $this->card_pin,'card_no'=>$this->card_no, 'status' => 0])) {
                    $r_h = new RechargeHistory();
                    $r_h->customer_id = \Yii::$app->user->getId();
                    $r_h->recharge_card_id = $card->id;
                    $r_h->user_agent=Yii::$app->request->getUserAgent();
                    $r_h->created_at = date("Y-m-d H:i:s");
                    if (!$r_h->save(false)) {
                        throw new \Exception(serialize($r_h->errors));
                    }
                    $c_t = new CustomerTransaction();
                    $c_t->customer_id = \Yii::$app->user->getId();
                    $c_t->trade_no = time();
                    $c_t->description = '充值卡充值|充值金额：' . $card->value . '元';
                    $c_t->amount = $card->value;
                    $c_t->date_added = date("Y-m-d H:i:s");
                    if (!$c_t->save(false)) {
                        throw new \Exception(serialize($c_t->errors));
                    }
                    $card->status = 1;
                    if (!$card->save(false)) {
                        throw new \Exception(serialize($card->errors));
                    }
                    if($card->card_code=='Hisense' && $card->value > 150 && time()<strtotime('2016-10-01') ){
                            if($coupon=Coupon::findOne(['code'=>'ECP160903002'])){
                                $customer_coupon=new CustomerCoupon();
                                $customer_coupon->coupon_id=$coupon->coupon_id;
                                $customer_coupon->customer_id=Yii::$app->user->getId();
                                $customer_coupon->description="企业福利";
                                $customer_coupon->is_use=0;
                                $customer_coupon->date_added=date('Y-m-d H:i:s',time());
                                $customer_coupon->save();
                                if (!$customer_coupon->save(false)) {
                                    throw new \Exception(serialize($customer_coupon->errors));
                                }
                            }
                    }
                $transaction->commit();
           }

                return $card;
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new \Exception("您输入的充值码无效");
            }
        }else{
            return false;
        }

    }
}
