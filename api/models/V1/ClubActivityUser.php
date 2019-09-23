<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_activity_user}}".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $activity_items_id
 * @property integer $customer_id
 * @property string $username
 * @property string $telephone
 * @property integer $quantity
 * @property string $total
 * @property string $remark
 * @property integer $status
 * @property string $creat_at
 * @property string $update_at
 */
class ClubActivityUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_activity_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'activity_items_id', 'customer_id', 'quantity', 'status'], 'integer'],
            [['total'], 'number'],
            [['remark'], 'string'],
            [['creat_at', 'update_at'], 'safe'],
            [['username'], 'string', 'max' => 255],
            [['telephone'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'activity_items_id' => 'Activity Items ID',
            'customer_id' => '用户 ID',
            'username' => '姓名',
            'telephone' => '电话',
            'quantity' => '数量',
            'total' => '总计',
            'remark' => '备注',
            'status' => '状态',
            'creat_at' => '下单时间',
            'update_at' => 'Update At',
            'begin_date' => '开始时间',
            'end_date' =>'结束时间',
            'order_id' => '订单 ID'
        ];
    }
    public function getActivity(){
        return $this->hasOne(ClubActivity::className(),['id'=>'activity_id']);
    }
    public function getActivityItems(){
        return $this->hasOne(ClubActivityItems::className(),['activity_id'=>'activity_id','id'=>'activity_items_id']);
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
    public function getTickets(){
        return $this->hasMany(ClubActivityUserTicket::className(),['activity_user_id'=>'id']);
    }
    public function getUseTickets(){
        return $this->hasMany(ClubActivityUserTicket::className(),['activity_user_id'=>'id'])->andOnCondition(['status'=>1]);
    }
    public function getOrder(){
        return $this->hasOne(Order::className(),['order_id'=>'order_id']);
    }
}
