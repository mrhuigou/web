<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 9:46
 */
namespace h5\models;
use api\models\V1\Address;
use api\models\V1\ClubTry;
use api\models\V1\ClubTryUser;
use api\models\V1\ClubUserInvite;
use api\models\V1\ClubUserInviteLog;
use api\models\V1\CustomerCoupon;
use api\models\V1\VerifyCode;
use yii\base\Model;
use Yii;
use yii\web\NotFoundHttpException;

class ClubTryForm extends Model{
    public $address_id;
    public $address;
    public $try_id;
    public $try;
    public $code;

    public function __construct($address_id,$try_id,$config = [])
    {
        if($model=ClubTry::findOne(['id'=>$try_id])){
            $this->address_id=$address_id;
            $this->try_id=$try_id;
            $this->address=Address::findOne(['address_id'=>$address_id]);
            $this->try=$model;
        }else{
            throw new NotFoundHttpException("没有找到免费试商品！");
        }

    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address_id','code'],'required'],
            [['address_id'], 'integer'],
            [['address_id'], 'checkUser'],
            ['code','checkCode'],
        ];
    }
    public function checkCode($attribute, $params){
        if($model=VerifyCode::findOne(['phone'=>Yii::$app->user->identity->telephone,'code'=>$this->code,'status'=>0])){
            $model->status=1;
            $model->update();
        }else{
            $this->addError($attribute,'验证码不正确！');
        }
    }
    public function checkUser($attribute,$params){
        if($this->address_id) {
            if ($this->try->limit_user > 0) {
                $total = ClubTryUser::find()->where(['customer_id' => Yii::$app->user->getId(), 'try_id' => $this->try_id])->count();
                if ($this->try->limit_user <= $total) {
                    $this->addError($attribute, '');
                    Yii::$app->getSession()->setFlash('error', "您已经申请过了！");
                }
            }
        }else{
            $this->addError($attribute, '');
            Yii::$app->getSession()->setFlash('error', "请添加你的收货地址！");
        }
    }
    public function attributeLabels()
    {
        return [
            'address_id' => '收货地址',
            'code' => '手机验证码',
        ];
    }
    public function save(){
        if($this->validate()){
            $model=new ClubTryUser();
            $model->try_id=$this->try_id;
            $model->customer_id=Yii::$app->user->getId();
            $model->shipping_name=$this->address->firstname;
            $model->shipping_telephone=$this->address->telephone;
            $model->zone_id=$this->address->zone_id;
            $model->city_id=$this->address->city_id;
            $model->district_id=$this->address->district_id;
            $model->address=$this->address->address_1;
            $model->postcode=$this->address->postcode;
            $model->status=0;
            $model->creat_at=date('Y-m-d H:i:s',time());
            $model->save();
            if($invite_code=\Yii::$app->session->get('invite_code')){
                if($invite= ClubUserInvite::findOne(['code'=>$invite_code,'type'=>'try','type_id'=>$this->try_id])){
                    \Yii::$app->session->remove('invite_code');
                    if(!$log=ClubUserInviteLog::findOne(['invite_id'=>$invite->id,'customer_id'=>\Yii::$app->user->getId()])){
                        $log=new ClubUserInviteLog();
                        $log->invite_id=$invite->id;
                        $log->customer_id=Yii::$app->user->getId();
                        $log->creat_at=date('Y-m-d H:i:s',time());
                        $log->save();
                    }
                    if( $model->try->limit_share_user <= count($invite->log)){
                        if($order=ClubTryUser::findOne(['customer_id'=>$invite->customer_id,'try_id'=>$invite->type_id,'status'=>0])){
                            $order->status=1;
                            $order->save();
                        }
                    }
                }
            }
            if($model->try->tryCoupon){
                foreach($model->try->tryCoupon as $v){
                    $coupon=new CustomerCoupon();
                    $coupon->customer_id=Yii::$app->user->getId();
                    $coupon->coupon_id=$v->coupon_id;
                    $coupon->description="";
                    $coupon->is_use=0;
                    $coupon->date_added=date('Y-m-d H:i:s',time());
                    $coupon->save();
                }
            }
            return $model;
        }else{
            return null;
        }
    }

}