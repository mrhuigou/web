<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_transaction}}".
 *
 * @property integer $customer_transaction_id
 * @property integer $customer_id
 * @property integer $trade_no
 * @property string $description
 * @property string $amount
 * @property string $date_added
 */
class CustomerTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'description', 'amount', 'date_added'], 'required'],
            [['customer_id'], 'integer'],
            [['description', 'trade_no'], 'string'],
            [['amount'], 'number'],
            [['date_added'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_transaction_id' => 'Customer Transaction ID',
            'customer_id' => 'Customer ID',
            'trade_no' => '交易号',
            'description' => '说明',
            'amount' => '金额',
            'date_added' => '时间',
        ];
    }
}
