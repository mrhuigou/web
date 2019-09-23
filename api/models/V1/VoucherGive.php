<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%voucher_give}}".
 *
 * @property integer $voucher_give_id
 * @property integer $customer_voucher_id
 * @property integer $customer_id
 * @property integer $voucher_id
 * @property integer $to_customer_id
 * @property string $to_name
 * @property string $message
 * @property string $active_code
 * @property string $date_added
 * @property string $date_modified
 * @property integer $status
 */
class VoucherGive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%voucher_give}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_voucher_id', 'customer_id', 'voucher_id', 'to_customer_id'], 'required'],
            [['customer_voucher_id', 'customer_id', 'voucher_id', 'to_customer_id', 'status'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['to_name'], 'string', 'max' => 100],
            [['message'], 'string', 'max' => 255],
            [['active_code'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'voucher_give_id' => 'Voucher Give ID',
            'customer_voucher_id' => 'customer_voucher.id',
            'customer_id' => '赠出人ID',
            'voucher_id' => 'Voucher ID',
            'to_customer_id' => '收赠人ID',
            'to_name' => '收赠人名称',
            'message' => '留言内容',
            'active_code' => 'Active Code',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'status' => '生效状态 ,默认0，确认1，拒绝2，追回3',
        ];
    }
}
