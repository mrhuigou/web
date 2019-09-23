<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_merge}}".
 *
 * @property integer $merge_id
 * @property string $merge_code
 * @property string $order_ids
 * @property string $total
 * @property integer $customer_id
 * @property string $payment_code
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 * @property string $remark
 * @property integer $type_id
 * @property string $input_total
 */
class OrderMerge extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_merge}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merge_code', 'order_ids', 'total'], 'required']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'merge_id' => 'Merge ID',
            'merge_code' => 'Merge Code',
            'order_ids' => 'Order Ids',
            'total' => 'Total',
            'customer_id' => 'Customer ID',
            'payment_code' => '支付方式编码 ',
            'status' => '0,未支付，1为支付成功',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'remark' => 'Remark',
            'type_id' => '支付类型，0=普通支付，1=到店后再次支付（就餐订单）',
            'input_total' => '再次支付时的输入金额',
        ];
    }
    public function getMergeTotal(){
        $total=0;
        $orderids=explode(",",$this->order_ids);
        if($orderids){
            foreach($orderids as $order_id){
                if($model=Order::findOne(['order_id'=>$order_id])){
                    $paid_total=0;
                    if($model->orderPayments){
                        foreach($model->orderPayments as $val){
                            $paid_total=bcadd($val->total,$paid_total,4);
                        }
                    }
                    $total=bcadd($total,bcsub($model->total,$paid_total,4),4);
                    $total = round($total,2);
                }
            }
        }
        return $total;
    }
    public function getPayStatus(){
    	$status=true;
    	foreach ($this->getOrder() as $order){
    		if($order->order_status_id!==1){
			    $status=false;
			    break;
		    }
	    }
	    return $status;
    }
    public function getOrder(){
        $data=[];
        $orderids=explode(",",$this->order_ids);
        if($orderids){
            foreach($orderids as $order_id){
                if($model=Order::findOne(['order_id'=>$order_id])){
                    $data[]=$model;
                }
            }
        }
        return $data;
    }
    public function getRecharge_status(){
        $data=false;
        $orderids=explode(",",$this->order_ids);
        if($orderids){
            foreach($orderids as $order_id){
                if($model=Order::findOne(['order_id'=>$order_id])){
                    if($model->order_type_code=='recharge'){
                        $data=true;
                        break;
                    }
                }
            }
        }
        return $data;
    }
}
