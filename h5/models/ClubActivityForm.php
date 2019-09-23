<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 9:46
 */
namespace h5\models;
use api\models\V1\ClubActivity;
use api\models\V1\ClubActivityItems;
use api\models\V1\ClubActivityKv;
use api\models\V1\ClubActivityOption;
use api\models\V1\ClubActivityOptionValue;
use api\models\V1\ClubActivityUser;
use api\models\V1\ClubActivityUserInfo;
use api\models\V1\ClubActivityUserTicket;
use api\models\V1\ClubUserInvite;
use api\models\V1\ClubUserInviteLog;
use api\models\V1\Order;
use api\models\V1\OrderActivity;
use api\models\V1\OrderActivityOption;
use api\models\V1\OrderMerge;
use common\component\Helper\OrderSn;
use common\component\Helper\SequenceNumber;
use yii\base\Model;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class ClubActivityForm extends Model{
    public $activity;
    public $item;
    public $username;
    public $telephone;
    public $quantity=1;
    public $activitykv_datas;
    public $option_data;
    public $remark;
    public $activitykv;

    public function __construct($activity_id,$config = [])
    {
        if($model=ClubActivity::findOne(['id'=>$activity_id])){
            $this->activity=$model;
            if($model->items){
                foreach($model->items as $key=>$v){
                    if($key==0){
                        $this->item=$v->id;
                    }
                }
            }else{
                $this->item=0;
            }
            //$this->activitykv_datas = $model->activitykv;
        }else{
            throw new NotFoundHttpException("没有找到活动！");
        }
        $this->username=Yii::$app->user->identity->firstname;
        $this->telephone=Yii::$app->user->identity->telephone;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules= [
            [['quantity'], 'integer'],
            [['username','telephone','quantity'],'required'],
            ['telephone','string','length'=>11],
            ['remark','string'],
            ['quantity','integer','min'=>1],
            ['quantity','validateQty']
        ];
        if($this->activity->option){
            $rules[]=['option_data','required','message'=>'此项不能为空！'];
        }

//        if($this->activity->activitykv){
//            foreach ($this->activity->activitykv  as $kv){
//                if($kv->is_require){
//                    $rules[]=['activitykv_datas['.$kv->club_activity_kv_id.']','required','message'=>'此项不能为空！'];
//                }
//            }
//        }
        return $rules;

    }
    public function validateQty($attribute, $params){
        if($this->activity->items){
            if($item=ClubActivityItems::findOne(['activity_id'=>$this->activity->id,'id'=>$this->item])){
                if($item->quantity){
                    $total=max(0,($item->quantity-$item->qty));
                    if($this->quantity>$total){
                        $this->addError($attribute,'数量超出活动限定数量!');
                    }
                }else{
                    if($this->activity->qty && $this->quantity>($this->activity->qty-$this->activity->tickets) ){
                        $this->addError($attribute,'数量超出活动限定数量!');
                    }
                }
            }else{
                $this->addError('item','数据非法!');
            }
        }else{
            if($this->activity->quantity){
                $total=max(0,($this->activity->qty-$this->activity->tickets));
                if($this->quantity>$total){
                    $this->addError($attribute,'数量超出活动限定数量!');
                }
            }
        }
    }
    public function attributeLabels()
    {
        return [
            'username' => '姓名',
            'telephone'=>'手机',
            'item'=>'报名项目',
            'quantity'=>'报名数量',
            'remark'=>'备注',
        ];
    }
    public function getTotal(){
        $total=0;
        if($this->item){
            $ItemModel=ClubActivityItems::findOne(['id'=>$this->item]);
            if($ItemModel){
                $total=  $ItemModel->fee*$this->quantity;
            }
        }else{
            $total=0;
        }
        return $total;
    }
    public function save(){
        if($this->validate()){
	        $merge_total = 0;
            //订单主数据
	        $transaction = \Yii::$app->db->beginTransaction();
	        try {
            $Order_model = new Order();
            $Order_model->order_no = date('YmdHis') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $Order_model->order_type_code =  strtoupper('activity');
            $Order_model->platform_id = 1;
            $Order_model->platform_name = "智慧生活";
            $Order_model->platform_url = Yii::$app->request->getHostInfo();
            $Order_model->store_id = 1;
            $Order_model->store_name = '青岛家润';
            $Order_model->store_url = '';
            $Order_model->customer_group_id = Yii::$app->user->identity['customer_group_id'];
            $Order_model->customer_id = Yii::$app->user->getId();
            $Order_model->firstname = $this->username;
            $Order_model->lastname = '';
            $Order_model->email = Yii::$app->user->identity['email'];
            $Order_model->telephone = $this->telephone;
            $Order_model->gender = Yii::$app->user->identity['gender'];
            $Order_model->payment_method = "";
            $Order_model->payment_code = "";
            $Order_model->total = $this->getTotal();
            $Order_model->comment = $this->remark;
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
            $Order_model->invoice_temp = "不需要发票";
            $Order_model->invoice_title = "";
            $Order_model->trade_account = "";
            $Order_model->use_date = "";
            $Order_model->time_range = "";
            $Order_model->use_nums = "";
            $Order_model->use_code = "";
            $Order_model->delivery_type = "0";
            $Order_model->in_cod = 0;
            $Order_model->sent_to_erp = "N";
            $Order_model->sent_time = "";
            $Order_model->parent_id = "";
            $Order_model->cycle_store_id = "";
            $Order_model->cycle_id = "";
            $Order_model->periods = "";
            if (!$Order_model->save(false)) {
                throw new \Exception("订单数据异常");
            }
            $order_id=$Order_model->order_id;
            //活动信息写入
            $orderActivity=new OrderActivity();
            $orderActivity->order_id=$order_id;
            $orderActivity->activity_id=$this->activity->id;
            $orderActivity->activity_name=$this->activity->title;
            if($activity_item=ClubActivityItems::findOne(['id'=>$this->item])){
                $orderActivity->activity_item_id=$activity_item->id;
                $orderActivity->activity_item_name=$activity_item->name;
                $orderActivity->quantity=$this->quantity;
                $orderActivity->price=$activity_item->fee;
                $orderActivity->total=$this->quantity*$activity_item->fee;
	            $merge_total = bcadd($merge_total, $orderActivity->total, 2);
            }else{
                $orderActivity->activity_item_id=0;
                $orderActivity->activity_item_name='免费报名';
                $orderActivity->quantity=$this->quantity;
                $orderActivity->price=0;
                $orderActivity->total=0;
            }
            if (!$orderActivity->save(false)) {
                throw new \Exception("订单数据异常");
            }

            $order_activity_id=$orderActivity->order_activity_id;
            if($this->option_data){
                foreach($this->option_data as $key=>$value){
                    $activity_option=ClubActivityOption::findOne(['id'=>$key]);
                    if($activity_option){
                        $orderActivityOption=new OrderActivityOption();
                        $orderActivityOption->order_activity_id=$order_activity_id;
                        $orderActivityOption->order_id=$order_id;
                        $orderActivityOption->activity_option_id=$activity_option->id;
                        $orderActivityOption->activity_option_name=$activity_option->name;;
                        $orderActivityOption->activity_option_value_id=0;
                        $orderActivityOption->activity_option_value=is_array($value)?implode(',',$value):$value;
                        if (!$orderActivityOption->save(false)) {
                            throw new \Exception("订单数据异常");
                        }
                    }
                }
            }
//            $activity_user_model = ClubActivityUser::findOne(['activity_id'=>$this->activity->activity_id,'customer_id'=>Yii::$app->user->getId(),'status'=>1]);
//
//            $activity_user = new ClubActivityUser();
            if($this->activitykv_datas){
                foreach ($this->activitykv_datas as $key => $value){
                    ClubActivityUserInfo::deleteAll(['activity_id'=>$this->activity->id,'customer_id'=>Yii::$app->user->getId(),'club_activity_kv_id'=>$key]);
                    $club_activity_user_info = new ClubActivityUserInfo();
                    $club_activity_user_info->activity_id = $this->activity->id;
                    $club_activity_user_info->customer_id = Yii::$app->user->getId();
                    $club_activity_user_info->club_activity_kv_id = $key;
                    $club_activity_user_info->value = $value;

                    if (!$club_activity_user_info->save(false)) {
                        throw new \Exception("kv保存数据异常");
                    }
                }
            }

	        $model = new OrderMerge();
	        $model->merge_code = OrderSn::generateNumber();
	        $model->order_ids =  $Order_model->order_id;
	        $model->customer_id = Yii::$app->user->getId();
	        $model->total = $merge_total;
	        $model->status = 0;
	        $model->date_added = date("Y-m-d H:i:s");
	        $model->date_modified = date("Y-m-d H:i:s");
	        if (!$model->save(false)) {
		        throw new \Exception(json_encode($model->errors));
	        }
	        $trade_no = $model->merge_code;
		        $transaction->commit();
	        } catch (\Exception $e) {
		        $transaction->rollBack();
		        throw new NotFoundHttpException($e->getMessage());
	        }
            return $trade_no;
        }else{
            return null;
        }
    }

}