<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/10/29
 * Time: 13:38
 */
namespace frontend\modules\club\models;
use api\models\V1\ClubActivity;
use api\models\V1\ClubActivityItems;
use api\models\V1\ClubActivityOption;
use api\models\V1\Order;
use api\models\V1\OrderActivity;
use api\models\V1\OrderActivityOption;
use yii\base\Model;
use Yii;
class ActivityOrderForm extends Model{
    public $activity_id;
    public $activity_item_id;
    public $quantity;
    public $username;
    public $telephone;
    public $option;
    public function __construct($config = [])
    {
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id','activity_item_id','quantity','username','telephone'], 'required'],
            ['quantity','number','min'=>1],
            [['telephone','username' ],'filter', 'filter' => 'trim'],
            ['username', 'string', 'min' => 2],
            ['telephone', 'string', 'length' => 11],
            ['activity_id','check_act'],
            ['option','safe']
        ];
    }
    public function check_act($attribute, $params){
        if($model=ClubActivity::findOne(['id'=>$this->activity_id,'status'=>1])){
            if(strtotime($model->signup_end)<time()){
                $this->addError($attribute,"活动报名已经截止！");
            }
            if($model->items){
                if($model=ClubActivityItems::findOne(['id'=>$this->activity_item_id,'activity_id'=>$this->activity_id])){
                    if($model->quantity){
                        if($this->quantity>max($model->quantity-$model->qty,0)){
                            $this->addError($attribute,"名额不足！");
                        }
                    }
                }else{
                    $this->addError($attribute,"活动项目已经失效");
                }
            }else{
                $this->activity_item_id=0;
            }

        }else{
            $this->addError($attribute,"活动已经失效");
        }
    }
public function getTotal(){
    $total=0;
    if($this->activity_item_id){
        if($ClubActivityItems=ClubActivityItems::findOne(['id'=>$this->activity_item_id,'activity_id'=>$this->activity_id])){
            $total=$ClubActivityItems->fee*$this->quantity;
        }
    }
    return $total;
}
    public function save(){
        if ($this->validate()) {
            $transaction=\Yii::$app->db->beginTransaction();
            try {
            if($ClubActivity=ClubActivity::findOne(['id'=>$this->activity_id,'status'=>1])){
            $Order_model = new Order();
            $Order_model->order_no = date('YmdHis') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $Order_model->order_type_code = strtoupper('activity');
            $Order_model->platform_id = 1;
            $Order_model->platform_name = "智慧生活";
            $Order_model->platform_url = "http://www.mrhuigou.com/";
            $Order_model->store_id = 1;
            $Order_model->store_name = '每日惠购自营店';
            $Order_model->customer_group_id = Yii::$app->user->identity['customer_group_id'];
            $Order_model->customer_id = Yii::$app->user->getId();
            $Order_model->firstname = $this->username;
            $Order_model->telephone = $this->telephone;
            $Order_model->gender = Yii::$app->user->identity['gender'];
            $Order_model->total = $this->getTotal();
            $Order_model->order_status_id = 1;
            if(\Yii::$app->session->get('affiliate_id')){
                $affiliate_id=intval(Yii::$app->session->get('affiliate_id'));
            }else{
                $affiliate_id=0;
            }
            $Order_model->affiliate_id = $affiliate_id;
            $Order_model->commission = 0;
            $Order_model->language_id = 2;
            $Order_model->currency_id = 4;
            $Order_model->currency_code = 'CNY';
            $Order_model->currency_value = 1;
            $Order_model->ip = Yii::$app->request->userIP;
            $Order_model->user_agent = Yii::$app->request->userAgent;
            $Order_model->accept_language = Yii::$app->request->getPreferredLanguage();
            $Order_model->date_added = date("Y-m-d H:i:s", time());
            if (!$Order_model->save(false)) {
                throw new \Exception("订单数据异常");
            }
            $Order_Activity=new OrderActivity();
            $Order_Activity->order_id=$Order_model->order_id;
            $Order_Activity->activity_id=$this->activity_id;
            $Order_Activity->activity_name=$ClubActivity->title;
            if($this->activity_item_id){
                $ClubActivityItems=ClubActivityItems::findOne(['id'=>$this->activity_item_id,'activity_id'=>$this->activity_id]);
                $Order_Activity->activity_item_id=$this->activity_item_id;
                $Order_Activity->activity_item_name=$ClubActivityItems->name;
                $Order_Activity->price=$ClubActivityItems->fee;
            }else{
                $Order_Activity->activity_item_id=0;
                $Order_Activity->activity_item_name='免费报名';
                $Order_Activity->price=0;
            }
            $Order_Activity->quantity=$this->quantity;
            $Order_Activity->total=$Order_Activity->price*$this->quantity;
            if (!$Order_Activity->save(false)) {
                throw new \Exception("订单活动数据异常");
            }
            if($this->option){
                foreach($this->option as $key=>$option){
                    if($optionModel=ClubActivityOption::findOne(['activity_id'=>$this->activity_id,'id'=>$key])){
                        $Order_Activity_Option=new OrderActivityOption();
                        $Order_Activity_Option->order_id=$Order_model->order_id;
                        $Order_Activity_Option->order_activity_id=$Order_Activity->order_activity_id;
                        $Order_Activity_Option->activity_option_id=$optionModel->id;
                        $Order_Activity_Option->activity_option_name=$optionModel->name;
                        $Order_Activity_Option->activity_option_value_id=0;
                        $Order_Activity_Option->activity_option_value=is_array($option)?implode(',',$option):$option;
                        if (!$Order_Activity_Option->save(false)) {
                            throw new \Exception("订单活动数据异常");
                        }
                    }
                }
            }
            $transaction->commit();
            return $Order_model;
            }else{
                throw new \Exception("数据异常");
            }
            }catch (\Exception $e){
                $transaction->rollBack();
                return false;
            }
        }else{
            return false;
        }
    }
    public function attributeLabels(){
        return ['username'=>'姓名',
            'telephone'=>'手机号',
            'quantity'=>'数量',
            'activity_id'=>'活动',
            'activity_item_id'=>'活动项目'
        ];
    }



}
