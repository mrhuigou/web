<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_store_to_product}}".
 *
 * @property integer $category_store_to_product_id
 * @property integer $category_store_id
 * @property string $category_store_code
 * @property integer $product_base_id
 * @property string $product_base_code
 */
class CategoryStoreToProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_store_to_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_store_id', 'category_store_code', 'product_base_id', 'product_base_code'], 'required'],
            [['category_store_id',  'product_base_id'], 'integer'],
            [['category_store_code',  'product_base_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_store_to_product_id' => 'Category Store To Product ID',
            'category_store_id' => 'Category Store ID',
            'category_store_code' => 'Category Store Code',
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
        ];
    }
}
