<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_payment}}".
 *
 * @property integer $order_payment_id
 * @property integer $order_id
 * @property integer $customer_id
 * @property string $payment_firstname
 * @property string $payment_deal_no
 * @property string $payment_method
 * @property string $payment_code
 * @property string $total
 * @property string $remark
 * @property string $date_added
 * @property integer $type_id
 * @property string $input_total
 * @property integer $is_send
 * @property string $send_time
 */
class OrderPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'customer_id', 'total', 'payment_code'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_payment_id' => 'Order Payment ID',
            'order_id' => '订单 ID',
            'customer_id' => 'Customer ID',
            'payment_firstname' => '付款人姓名',
            'payment_deal_no' => '交易号',
            'payment_method' => '付款方式',
            'payment_code' => '支付编码',
            'total' => '总计',
            'remark' => '备注',
            'date_added' => '支付时间',
            'type_id' => '支付类型，0=普通支付，1=到店后再次支付（就餐订单）',
            'input_total' => '再次支付时 输入的金额',
            'is_send' => '是否传送给后台',
            'send_time' => '传送时间',
        ];
    }
}
