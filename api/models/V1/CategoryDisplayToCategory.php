<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_display_to_category}}".
 *
 * @property string $category_display_to_category_id
 * @property integer $category_display_id
 * @property integer $category_id
 * @property string $category_code
 * @property integer $sort_order
 */
class CategoryDisplayToCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_display_to_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_display_id', 'category_id'], 'required'],
            [['category_display_id', 'category_id', 'sort_order'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_display_to_category_id' => 'Category Display To Category ID',
            'category_display_id' => 'Category Display ID',
            'category_id' => 'Category ID',
            'category_code' => 'Category Code',
            'sort_order' => 'Sort Order',
        ];
    }
}
