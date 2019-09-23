<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%voucher}}".
 *
 * @property integer $voucher_id
 * @property integer $order_id
 * @property string $acitive_code
 * @property string $code
 * @property string $from_name
 * @property string $from_email
 * @property string $to_name
 * @property string $to_email
 * @property integer $voucher_theme_id
 * @property string $message
 * @property string $amount
 * @property integer $status
 * @property integer $on_sale
 * @property string $date_added
 */
class Voucher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%voucher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'acitive_code', 'code', 'from_name', 'from_email', 'to_name', 'to_email', 'voucher_theme_id', 'message', 'amount', 'status', 'on_sale'], 'required'],
            [['order_id', 'voucher_theme_id', 'status', 'on_sale'], 'integer'],
            [['message'], 'string'],
            [['amount'], 'number'],
            [['date_added'], 'safe'],
            [['acitive_code'], 'string', 'max' => 255],
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
            'voucher_id' => 'Voucher ID',
            'order_id' => 'Order ID',
            'acitive_code' => 'Acitive Code',
            'code' => 'Code',
            'from_name' => 'From Name',
            'from_email' => 'From Email',
            'to_name' => 'To Name',
            'to_email' => 'To Email',
            'voucher_theme_id' => 'Voucher Theme ID',
            'message' => 'Message',
            'amount' => 'Amount',
            'status' => 'Status',
            'on_sale' => 'On Sale',
            'date_added' => 'Date Added',
        ];
    }
}
