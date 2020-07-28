<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%return_base}}".
 *
 * @property integer $return_id
 * @property string $return_code
 * @property integer $order_id
 * @property string $order_code
 * @property string $order_no
 * @property string $date_ordered
 * @property integer $customer_id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $telephone
 * @property integer $return_status_id
 * @property string $comment
 * @property string $total
 * @property string $date_added
 * @property string $date_modified
 * @property integer $send_status
 * @property integer $is_all_return
 * @property string $return_method
 */
class ReturnBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%return}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'customer_id', 'return_status_id', 'send_status', 'is_all_return'], 'integer'],
            [['order_id', 'customer_id', 'return_status_id', 'total'], 'required'],
            [['date_ordered', 'date_added', 'date_modified'], 'safe'],
            [['comment','return_method'], 'string'],
            [['total'], 'number'],
            [['return_code', 'order_code', 'order_no'], 'string', 'max' => 125],
            [['firstname', 'lastname', 'telephone'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 96]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'return_id' => '退货单 ID',
            'return_code' => '退货编码',
            'order_id' => '订单 ID',
            'order_code' => '订单 Code',
            'order_no' => '订单编号',
            'date_ordered' => '订单日期',
            'customer_id' => '用户 ID',
            'firstname' => '姓名',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'telephone' => '电话',
            'return_status_id' => '退货状态',
            'comment' => '备注',
            'total' => '金额',
            'date_added' => '退货时间',
            'date_modified' => '最后更新时间',
            'send_status' => '是否同步到后台',
            'is_all_return' => '是否全退',
	        'return_method'=>'退换货类型'
        ];
    }
    public function getReturnType(){
        $type=[
        	'RETURN_GOODS'=>'退货',
	        'RETURN_PAY'=>'退款',
	        'RESHIP'=>'换货'
        ];
        return $this->return_method?$type[$this->return_method]:'退货';
    }
    public function getReturnProduct(){
        return $this->hasMany(ReturnProduct::className(), ['return_id' => 'return_id']);
    }
    public function getReturnStatus(){
        return $this->hasOne(ReturnStatus::className(), ['return_status_id' => 'return_status_id']);
    }
    public function getOrder(){
        return $this->hasOne(Order::className(),['order_id'=>'order_id']);
    }
    public function getTotals(){
    	return $this->hasMany(ReturnTotal::className(),['return_id'=>'return_id']);
    }
    public function getHistory(){
    	return $this->hasMany(ReturnHistory::className(),['return_id'=>'return_id']);
    }

    public function getOrderShipping(){
        return $this->hasOne(OrderShipping::className(),['order_id'=>'order_id']);
    }
}
