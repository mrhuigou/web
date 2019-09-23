<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_commission_draw}}".
 *
 * @property integer $id
 * @property string $code
 * @property integer $customer_id
 * @property string $amount
 * @property integer $status
 * @property integer $created_at
 * @property integer $update_at
 * @property string $trade_no
 * @property string $remark
 * @property string $open_id
 */
class CustomerCommissionDraw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_commission_draw}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'status', 'created_at', 'update_at'], 'integer'],
            [['amount'], 'number'],
            [['remark'], 'string'],
            [['code', 'trade_no','open_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'customer_id' => 'Customer ID',
            'amount' => 'Amount',
            'status' => 'Status',
            'created_at' => 'Created At',
            'update_at' => 'Update At',
            'trade_no' => 'Trade No',
            'remark' => 'Remark',
        ];
    }
    public function getCustomer(){
    	return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
}
