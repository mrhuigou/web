<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_to_brand}}".
 *
 * @property integer $category_id
 * @property integer $brand_id
 */
class CategoryToBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_to_brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'brand_id'], 'required'],
            [['category_id', 'brand_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'brand_id' => 'Brand ID',
        ];
    }
}
