<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_display_to_platform}}".
 *
 * @property string $category_display_to_platform_id
 * @property integer $category_display_id
 * @property integer $platform_id
 * @property string $platform_code
 */
class CategoryDisplayToPlatform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_display_to_platform}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_display_id'], 'required'],
            [['category_display_id', 'platform_id'], 'integer'],
            [['platform_code'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_display_to_platform_id' => 'Category Display To Platform ID',
            'category_display_id' => 'Category Display ID',
            'platform_id' => 'Platform ID',
            'platform_code' => 'Platform Code',
        ];
    }
}
