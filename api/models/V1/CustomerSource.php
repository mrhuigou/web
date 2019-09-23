<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_source}}".
 *
 * @property integer $customer_source_id
 * @property integer $customer_id
 * @property string $source_from_type
 * @property integer $source_from_table
 * @property integer $source_from_id
 * @property string $date_added
 * @property string $code
 * @property integer $is_new_customer
 */
class CustomerSource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_source}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'source_from_type','source_from_id', 'is_new_customer'], 'integer'],
            [['date_added'], 'safe'],
            [['code','source_from_table'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_source_id' => 'Customer Source ID',
            'customer_id' => 'Customer ID',
            'source_from_type' => 'Source From Code',
            'source_from_table' => 'Source From Table',
            'source_from_id' => 'Source From ID',
            'date_added' => 'Date Added',
            'code' => 'code',
            'is_new_customer' => 'Is New Customer',
        ];
    }
}
