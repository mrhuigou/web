<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_to_store}}".
 *
 * @property integer $category_id
 * @property integer $platform_id
 */
class CategoryToStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_to_store}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'platform_id'], 'required'],
            [['category_id', 'platform_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'platform_id' => 'Platform ID',
        ];
    }
}
