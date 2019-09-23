<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_display_to_brand}}".
 *
 * @property integer $id
 * @property integer $category_display_id
 * @property integer $brand_id
 * @property string $image
 * @property integer $sort_order
 */
class CategoryDisplayToBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_display_to_brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_display_id', 'brand_id', 'sort_order'], 'integer'],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_display_id' => 'Category Display ID',
            'brand_id' => 'Brand ID',
            'image' => 'Image',
            'sort_order' => 'Sort Order',
        ];
    }
}
