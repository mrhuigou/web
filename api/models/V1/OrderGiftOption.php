<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_gift_option}}".
 *
 * @property integer $order_gift_option_id
 * @property integer $order_id
 * @property integer $order_gift_id
 * @property integer $product_option_id
 * @property integer $product_option_value_id
 * @property integer $option_id
 * @property string $option_type
 * @property string $option_name
 * @property integer $option_value_id
 * @property string $option_value_name
 */
class OrderGiftOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_gift_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_gift_id', 'product_option_id', 'option_id', 'option_type', 'option_value_id'], 'required'],
            [['order_id', 'order_gift_id', 'product_option_id', 'product_option_value_id', 'option_id', 'option_value_id'], 'integer'],
            [['option_type'], 'string', 'max' => 32],
            [['option_name', 'option_value_name'], 'string', 'max' => 125]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_gift_option_id' => 'Order Gift Option ID',
            'order_id' => 'Order ID',
            'order_gift_id' => 'Order Gift ID',
            'product_option_id' => 'Product Option ID',
            'product_option_value_id' => 'Product Option Value ID',
            'option_id' => 'Option ID',
            'option_type' => 'Option Type',
            'option_name' => 'Option Name',
            'option_value_id' => 'Option Value ID',
            'option_value_name' => 'Option Value Name',
        ];
    }
}
