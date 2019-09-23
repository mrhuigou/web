<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_commission_flow}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $title
 * @property string $amount
 * @property string $balance
 * @property string $reverse
 * @property string $remark
 * @property integer $create_at
 * @property integer $status
 * @property integer $update_at
 * @property string $type
 * @property integer $type_id
 * @property integer $aff_type
 * @property integer $is_notice
 */
class CustomerCommissionFlow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_commission_flow}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'create_at', 'status', 'update_at','type_id'], 'integer'],
            [['amount', 'balance', 'reverse'], 'number'],
            [['remark'], 'string'],
            [['title','type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'title' => 'Title',
            'amount' => 'Amount',
            'balance' => 'Balance',
            'reverse' => 'Reverse',
            'remark' => 'Remark',
            'create_at' => 'Create At',
            'status' => 'Status',
            'update_at' => 'Update At',
	        'type'=>'Type',
	        'type_id'=>'Type ID'
        ];
    }
}
