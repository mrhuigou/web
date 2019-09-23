<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%tax_rate_to_customer_group}}".
 *
 * @property integer $tax_rate_id
 * @property integer $customer_group_id
 */
class TaxRateToCustomerGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tax_rate_to_customer_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tax_rate_id', 'customer_group_id'], 'required'],
            [['tax_rate_id', 'customer_group_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tax_rate_id' => 'Tax Rate ID',
            'customer_group_id' => 'Customer Group ID',
        ];
    }
}
