<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_option_value}}".
 *
 * @property integer $product_option_value_id
 * @property integer $product_option_id
 * @property integer $option_id
 * @property string $option_code
 * @property integer $option_value_id
 * @property string $option_value_code
 * @property string $option_value_name
 */
class ProductOptionValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_option_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_option_id', 'option_id', 'option_code', 'option_value_id', 'option_value_code', 'option_value_name'], 'required'],
            [['product_option_id', 'option_id', 'option_value_id'], 'integer'],
            [['option_code', 'option_value_code'], 'string', 'max' => 32],
            [['option_value_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_option_value_id' => 'Product Option Value ID',
            'product_option_id' => 'Product Option ID',
            'option_id' => 'Option ID',
            'option_code' => 'Option Code',
            'option_value_id' => 'Option Value ID',
            'option_value_code' => 'Option Value Code',
            'option_value_name' => 'Option Value Name',
        ];
    }
}
