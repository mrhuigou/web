<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_image}}".
 *
 * @property integer $product_image_id
 * @property integer $product_base_id
 * @property integer $product_id
 * @property string $product_code
 * @property string $image
 * @property integer $sort_order
 */
class ProductImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_base_id', 'product_id', 'product_code', 'image'], 'required'],
            [['product_base_id', 'product_id', 'sort_order'], 'integer'],
            [['product_code'], 'string', 'max' => 32],
            [['image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_image_id' => 'Product Image ID',
            'product_base_id' => 'Product Base ID',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'image' => 'Image',
            'sort_order' => 'Sort Order',
        ];
    }
}
