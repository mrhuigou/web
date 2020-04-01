<?php
namespace fx\models;
use api\models\V1\CheckoutOrder;
use api\models\V1\CustomerTransaction;
use api\models\V1\OrderMerge;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use Yii;
class BalanceForm extends Model{
    public $password;
    public $trade_order;
    public function __construct($trade_no = 0,$config = [])
    {
        if(!$this->trade_order=OrderMerge::findOne(['merge_code'=>$trade_no])){
            throw new NotFoundHttpException("非法操作！");
        }
        parent::__construct($config);
    }
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['password'],  'string','length'=>6],
            [['password'],  'checkPassword'],
        ];
    }
    public function checkPassword($attribute){
        $model=Yii::$app->user->identity;
         if(!$model->validatePayPassword($this->password)){
             $this->addError($attribute,'支付密码错误,请重试！');
         }
        if(bccomp(Yii::$app->user->identity->balance,$this->trade_order->mergeTotal,2)<0){
            $this->addError($attribute,'余额不足,请先充值后使用！');
        }
        if($this->trade_order->status==1){
            $this->addError($attribute,'订单已经支付过了！');
        }
    }
    public function attributeLabels(){
        return ['password'=>'支付密码'];
    }
    public function submit(){
        if($this->validate()){
            $transaction=\Yii::$app->db->beginTransaction();
            try{
                $balance=new CustomerTransaction();
                $balance->customer_id=Yii::$app->user->getId();
                $balance->amount='-'.$this->trade_order->mergeTotal;
                $balance->trade_no=$this->trade_order->merge_code;
                $balance->description="购物消费|流水号：".$this->trade_order->merge_code;
                $balance->date_added=date('Y-m-d H:i:s',time());
                if(!$balance->save()){
                    throw new \Exception("扣款失败！");
                }
                $model=new CheckoutOrder();
                $model->out_trade_no=$this->trade_order->merge_code;
                $model->transaction_id=$this->trade_order->merge_code;
                $model->staus=2;
                $model->payment_method="余额支付";
                $model->payment_code="balance";
                $model->remak=$this->trade_order->merge_code;
                if(!$model->save()){
                    throw new \Exception("更新失败！");
                }
                $transaction->commit();
                return true;
            }catch (\Exception $e){
                $transaction->rollBack();
                $this->addError('password','系统繁忙，稍后再试！');
                return false;
            }
        }else{
            return false;
        }

    }
}