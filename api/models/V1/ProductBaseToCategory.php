<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_base_to_category}}".
 *
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $category_id
 * @property string $category_code
 */
class ProductBaseToCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_base_to_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_base_id', 'product_base_code', 'category_id', 'category_code'], 'required'],
            [['product_base_id', 'category_id'], 'integer'],
            [['product_base_code', 'category_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
            'category_id' => 'Category ID',
            'category_code' => 'Category Code',
        ];
    }
}
