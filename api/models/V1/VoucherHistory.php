<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%voucher_history}}".
 *
 * @property integer $voucher_history_id
 * @property integer $voucher_id
 * @property integer $order_id
 * @property integer $customer_id
 * @property string $amount
 * @property string $date_added
 * @property integer $status
 * @property string $remark
 */
class VoucherHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%voucher_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voucher_id', 'order_id', 'amount', 'date_added'], 'required'],
            [['voucher_id', 'order_id', 'customer_id', 'status'], 'integer'],
            [['amount'], 'number'],
            [['date_added'], 'safe'],
            [['remark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'voucher_history_id' => 'Voucher History ID',
            'voucher_id' => 'Voucher ID',
            'order_id' => 'Order ID',
            'customer_id' => '消费客户',
            'amount' => 'Amount',
            'date_added' => 'Date Added',
            'status' => '1表示占用，2表示已经使用成功',
            'remark' => '备注说明',
        ];
    }
}
