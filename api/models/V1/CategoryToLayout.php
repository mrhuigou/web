<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_to_layout}}".
 *
 * @property integer $category_id
 * @property integer $store_id
 * @property integer $layout_id
 */
class CategoryToLayout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_to_layout}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'store_id', 'layout_id'], 'required'],
            [['category_id', 'store_id', 'layout_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'store_id' => 'Store ID',
            'layout_id' => 'Layout ID',
        ];
    }
}
