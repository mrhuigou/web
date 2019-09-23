<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_field}}".
 *
 * @property integer $order_id
 * @property integer $custom_field_id
 * @property integer $custom_field_value_id
 * @property integer $name
 * @property string $value
 * @property integer $sort_order
 */
class OrderField extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_field}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'custom_field_id', 'custom_field_value_id', 'name', 'value', 'sort_order'], 'required'],
            [['order_id', 'custom_field_id', 'custom_field_value_id', 'name', 'sort_order'], 'integer'],
            [['value'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'custom_field_id' => 'Custom Field ID',
            'custom_field_value_id' => 'Custom Field Value ID',
            'name' => 'Name',
            'value' => 'Value',
            'sort_order' => 'Sort Order',
        ];
    }
}
