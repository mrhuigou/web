<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_display_description}}".
 *
 * @property string $category_display_description_id
 * @property integer $category_display_id
 * @property string $name
 * @property string $image
 * @property string $description
 * @property string $banner_color
 * @property integer $language_id
 * @property string $meta_description
 * @property string $meta_keyword
 * @property string $color
 * @property string $hot_search
 */
class CategoryDisplayDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_display_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_display_id', 'language_id'], 'integer'],
            [['description', 'meta_description', 'meta_keyword', 'hot_search'], 'string'],
            [['name', 'image'], 'string', 'max' => 255],
            [['banner_color', 'color'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_display_description_id' => 'Category Display Description ID',
            'category_display_id' => 'Category Display ID',
            'name' => 'Name',
            'image' => 'Image',
            'description' => 'Description',
            'banner_color' => '分类banner色值，仅用于显示',
            'language_id' => 'Language ID',
            'meta_description' => 'Meta Description',
            'meta_keyword' => 'Meta Keyword',
            'color' => '分类色值,仅用于显示',
            'hot_search' => 'Hot Search',
        ];
    }
}
