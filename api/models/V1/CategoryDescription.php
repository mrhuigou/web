<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_description}}".
 *
 * @property integer $category_id
 * @property string $code
 * @property integer $language_id
 * @property string $name
 * @property string $description
 * @property string $meta_description
 * @property string $meta_keyword
 * @property integer $product_sorts_num
 * @property integer $product_sale_num
 * @property integer $ca_level
 * @property string $color
 * @property string $banner_color
 */
class CategoryDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'code', 'name'], 'required'],
            [['category_id', 'language_id', 'product_sorts_num', 'product_sale_num', 'ca_level'], 'integer'],
            [['description'], 'string'],
            [['code', 'name', 'meta_description', 'meta_keyword', 'color', 'banner_color'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'code' => 'Code',
            'language_id' => 'Language ID',
            'name' => 'Name',
            'description' => 'Description',
            'meta_description' => 'Meta Description',
            'meta_keyword' => 'Meta Keyword',
            'product_sorts_num' => 'Product Sorts Num',
            'product_sale_num' => 'Product Sale Num',
            'ca_level' => 'Ca Level',
            'color' => 'Color',
            'banner_color' => 'Banner Color',
        ];
    }
}
