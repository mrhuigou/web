<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%banner_image}}".
 *
 * @property integer $banner_image_id
 * @property integer $banner_id
 * @property string $link
 * @property string $image
 * @property integer $sort
 * @property string $code
 * @property string $date_start
 * @property string $date_end
 * @property integer $status
 * @property string $bg_color
 */
class BannerImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['banner_id', 'link', 'image'], 'required'],
            [['banner_id', 'sort', 'status'], 'integer'],
            [['date_start', 'date_end'], 'safe'],
            [['link', 'image', 'bg_color'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'banner_image_id' => 'Banner Image ID',
            'banner_id' => 'Banner ID',
            'link' => 'Link',
            'image' => 'Image',
            'sort' => 'Sort',
            'code' => 'Code',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'status' => '0停用1启用',
            'bg_color' => 'Bg Color',
        ];
    }
}
