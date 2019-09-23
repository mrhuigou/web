<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_option}}".
 *
 * @property integer $product_option_id
 * @property integer $product_id
 * @property string $product_code
 * @property integer $option_id
 * @property string $option_code
 * @property string $option_name
 * @property string $option_value
 * @property integer $required
 */
class ProductOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'product_code', 'option_id', 'option_code', 'option_name', 'option_value', 'required'], 'required'],
            [['product_id', 'option_id', 'required'], 'integer'],
            [['option_value'], 'string'],
            [['product_code', 'option_code'], 'string', 'max' => 32],
            [['option_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_option_id' => 'Product Option ID',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'option_id' => 'Option ID',
            'option_code' => 'Option Code',
            'option_name' => 'Option Name',
            'option_value' => 'Option Value',
            'required' => 'Required',
        ];
    }
}
