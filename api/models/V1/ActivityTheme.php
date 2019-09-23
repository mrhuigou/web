<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%activity_theme}}".
 *
 * @property integer $activity_theme_id
 * @property string $theme_description
 * @property string $theme_title
 * @property integer $status
 * @property string $date_added
 * @property string $theme_image
 * @property integer $sort_order
 * @property string $activity_code
 * @property string $theme_color
 */
class ActivityTheme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%activity_theme}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme_description'], 'string'],
            [['status', 'sort_order'], 'integer'],
            [['date_added'], 'safe'],
            [['theme_title', 'theme_image', 'activity_code'], 'string', 'max' => 255],
            [['theme_color'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_theme_id' => 'Activity Theme ID',
            'theme_description' => 'Theme Description',
            'theme_title' => 'Theme Title',
            'status' => '0表示停用，1表示启用',
            'date_added' => 'Date Added',
            'theme_image' => 'Theme Image',
            'sort_order' => 'Sort Order',
            'activity_code' => '促销活动的code码',
            'theme_color' => '主题背景颜色',
        ];
    }
}
