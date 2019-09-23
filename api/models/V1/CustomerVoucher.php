<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_voucher}}".
 *
 * @property string $customer_voucher_id
 * @property string $customer_id
 * @property string $voucher_id
 * @property string $description
 * @property string $date_added
 * @property string $date_modified
 * @property string $from_customer_id
 * @property integer $status
 * @property integer $give_status
 * @property integer $buy_give_type
 * @property string $active_code
 * @property integer $active_status
 */
class CustomerVoucher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_voucher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'voucher_id', 'from_customer_id', 'status', 'give_status', 'buy_give_type', 'active_status'], 'integer'],
            [['description'], 'string'],
            [['date_added', 'date_modified'], 'safe'],
            [['active_code'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_voucher_id' => 'Customer Voucher ID',
            'customer_id' => 'Customer ID',
            'voucher_id' => 'Voucher ID',
            'description' => 'Description',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'from_customer_id' => 'From Customer ID',
            'status' => '有效状态',
            'give_status' => '赠送状态,0=未赠送,1=赠送',
            'buy_give_type' => '买赠类型，0=购买，1=赠送',
            'active_code' => 'Active Code',
            'active_status' => '激活状态 0=未激活（默认），1=已激活',
        ];
    }
}
