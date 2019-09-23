<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_display_to_relate}}".
 *
 * @property integer $category_display_to_relate_id
 * @property integer $category_display_id
 * @property integer $product_id
 * @property string $product_code
 * @property string $product_base_code
 * @property integer $sort_order
 */
class CategoryDisplayToRelate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_display_to_relate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_display_id', 'product_id', 'product_code', 'product_base_code'], 'required'],
            [['category_display_id', 'product_id', 'sort_order'], 'integer'],
            [['product_code', 'product_base_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_display_to_relate_id' => 'Category Display To Relate ID',
            'category_display_id' => 'Category Display ID',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'product_base_code' => 'Product Base Code',
            'sort_order' => 'Sort Order',
        ];
    }
}
