<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%platform}}".
 *
 * @property string $platform_id
 * @property string $platform_code
 * @property string $platform_name
 * @property string $ssl
 * @property string $platform_url
 * @property string $date_added
 * @property string $date_modified
 * @property integer $status
 * @property string $city
 * @property string $zone
 * @property integer $radius
 */
class Platform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%platform}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['platform_code'], 'required'],
            [['date_added', 'date_modified'], 'safe'],
            [['status', 'radius'], 'integer'],
            [['platform_code'], 'string', 'max' => 50],
            [['platform_name', 'ssl', 'platform_url'], 'string', 'max' => 255],
            [['city', 'zone'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'platform_id' => 'Platform ID',
            'platform_code' => 'Platform Code',
            'platform_name' => 'Platform Name',
            'ssl' => 'Ssl',
            'platform_url' => 'Platform Url',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'status' => 'Status',
            'city' => '服务 省范围 存储zone code值',
            'zone' => '服务 省范围 存储zone code值',
            'radius' => '外卖等配送半径 单位M',
        ];
    }
}
