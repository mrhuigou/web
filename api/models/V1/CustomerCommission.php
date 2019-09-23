<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_commission}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $amount
 */
class CustomerCommission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_commission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'integer'],
            [['amount'], 'number'],
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
            'amount' => 'Amount',
        ];
    }
}
