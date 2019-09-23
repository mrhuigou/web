<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%banner_image_description}}".
 *
 * @property integer $banner_image_id
 * @property integer $language_id
 * @property integer $banner_id
 * @property string $title
 * @property string $theme_description
 */
class BannerImageDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner_image_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['banner_image_id', 'language_id', 'banner_id', 'title'], 'required'],
            [['banner_image_id', 'language_id', 'banner_id'], 'integer'],
            [['theme_description'], 'string'],
            [['title'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'banner_image_id' => 'Banner Image ID',
            'language_id' => 'Language ID',
            'banner_id' => 'Banner ID',
            'title' => 'Title',
            'theme_description' => 'Theme Description',
        ];
    }
}
