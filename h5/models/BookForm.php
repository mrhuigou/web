<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/30
 * Time: 16:12
 */
namespace h5\models;
use api\models\V1\BookCoupon;
use api\models\V1\BookOrder;
use yii\base\Model;
use Yii;
class BookForm extends Model{
    public $username;
    public $telephone;
    public $email;
    public $weixin;
    public $hope;
    public $qty=1;
    public $type=1;
    public $item;
    public $price;
    public $coupon;


    public function __construct($config = [])
    {
        if($config){
            $this->type=2;
            $this->item=[230=>'参加下午专题产区培训(230元/人)限40人',490=>'参加晚宴(490元/人)限12人',720=>'同时参加培训和晚宴（720/人）限12人'];
            $this->price=230;
        }else{
            $this->type=1;
            $this->item=[80=>'培训费（80元/人）'];
            $this->price=80;
        }
        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['username','telephone','email','weixin','qty','type','price'],"required"],
            [['hope','coupon'], 'string'],
            [['email'], 'email'],
            ['qty','number','min'=>1,'max'=>100],
            [['username','weixin'], 'string', 'max' => 125],
            ['telephone', 'string','length'=>11],
            ['telephone', 'checkUser'],
            ['coupon', 'checkCode'],
        ];
    }
    public function checkUser($attribute, $params){
        if($this->price==80){
            $this->type=1;
        }
        if($this->price==230){
            $this->type=2;
            $data=BookOrder::find()->where(['type'=>2,'status'=>1])->sum('quantity');
            if(($data+$this->qty)>40){
                $this->addError("qty",'已经超出上限40人，你最多可以报'.max((40-$data),0));
            }
        }
        if($this->price==490){
            $this->type=3;
            $data=BookOrder::find()->where(['type'=>2,'status'=>1])->sum('quantity');
            if(($data+$this->qty)>12){
                $this->addError("qty",'已经超出上限12人，你最多可以报'.max((12-$data),0));
            }
        }
        if($this->price==720){
            $this->type=4;
            $data=BookOrder::find()->where(['type'=>[3,4],'status'=>1])->sum('quantity');
            if(($data+$this->qty)>12){
                $this->addError("qty",'已经超出上限12人，你最多可以报'.max((12-$data),0));
            }
        }
       if($this->price!=80 && $user=BookOrder::findOne(['type'=>$this->type,'telephone'=>$this->telephone])){
           $this->addError($attribute,'此手机已经报过名了！');
       }
    }
    public function checkCode($attribute, $params){
        $coupon=BookCoupon::findOne(['code'=>$this->coupon]);
        if($coupon){
            if($coupon->status){
                $this->addError($attribute,'此支付码已被使用!');
            }
        }else{
            $this->addError($attribute,'您的支付码无效!');
        }
    }
    /**
     * @inheritdoc
     */
    public function reg(){
        if($this->validate()){
            if($this->price==80){
                $this->type=1;
            }
            if($this->price==230){
                $this->type=2;
            }
            if($this->price==490){
                $this->type=3;
            }
            if($this->price==720){
                $this->type=4;
            }
            $BookOrder=new BookOrder();
            $BookOrder->type=$this->type;
            $BookOrder->code=time().rand(1000,9999);
            $BookOrder->username=$this->username;
            $BookOrder->telephone=$this->telephone;
            $BookOrder->email=$this->email;
            $BookOrder->weixin=$this->weixin;
            $BookOrder->hope=$this->hope;
            $BookOrder->quantity=$this->qty;
            $BookOrder->total=$this->qty*$this->price;
            $BookOrder->status=0;
            $BookOrder->date_added=date('Y-m-d H:i:s',time());
            $BookOrder->save();
            if($cp=BookCoupon::findOne(['code'=>$this->coupon,'status'=>0])){
                $cp->status=1;
                $cp->save();
                $BookOrder->transaction_id=$this->coupon;
                $BookOrder->payment='贵宾通道';
                $BookOrder->payment_code='VIP';
                $BookOrder->remark=serialize(\Yii::$app->request->post());
                $BookOrder->status=1;
                $BookOrder->date_modify=date('Y-m-d H:i:s',time());
                $BookOrder->save();
            }
            return $BookOrder;
        }else{
            return null;
        }
    }
    public function attributeLabels()
    {
        return [
            'username' => '姓名',
            'telephone' => '手机号',
            'email' => '邮箱',
            'weixin' => '微信号',
            'qty'=>'报名人数',
            'hope' => '希望在课上学到什么',
            'price'=>'报名费用',
            'coupon' => '支付码(选填)',
        ];
    }


}