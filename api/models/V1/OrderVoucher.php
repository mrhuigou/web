<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_voucher}}".
 *
 * @property integer $order_voucher_id
 * @property integer $order_id
 * @property integer $voucher_id
 * @property string $description
 * @property string $code
 * @property string $from_name
 * @property string $from_email
 * @property string $to_name
 * @property string $to_email
 * @property string $acitive_code
 * @property integer $voucher_theme_id
 * @property string $message
 * @property string $amount
 */
class OrderVoucher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_voucher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'voucher_id', 'description', 'code', 'from_name', 'from_email', 'to_name', 'to_email', 'voucher_theme_id', 'message', 'amount'], 'required'],
            [['order_id', 'voucher_id', 'voucher_theme_id'], 'integer'],
            [['message'], 'string'],
            [['amount'], 'number'],
            [['description', 'acitive_code'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 10],
            [['from_name', 'to_name'], 'string', 'max' => 64],
            [['from_email', 'to_email'], 'string', 'max' => 96]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_voucher_id' => 'Order Voucher ID',
            'order_id' => 'Order ID',
            'voucher_id' => 'Voucher ID',
            'description' => 'Description',
            'code' => 'Code',
            'from_name' => 'From Name',
            'from_email' => 'From Email',
            'to_name' => 'To Name',
            'to_email' => 'To Email',
            'acitive_code' => 'Acitive Code',
            'voucher_theme_id' => 'Voucher Theme ID',
            'message' => 'Message',
            'amount' => 'Amount',
        ];
    }
}
