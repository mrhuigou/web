<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%app}}".
 *
 * @property integer $app_id
 * @property string $app_name
 * @property string $app_type
 * @property string $device_type
 * @property string $app_version
 * @property string $create_time
 * @property string $save_path
 * @property string $download_url
 * @property integer $is_force
 */
class App extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time'], 'safe'],
            [['app_name', 'app_type','app_version', 'save_path'], 'required'],
            [['is_force','app_version'], 'integer'],
            [['app_name', 'app_type', 'device_type', 'app_version', 'save_path', 'download_url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App ID',
            'app_name' => 'App 名称',
            'app_type' => 'App 类别',
            'device_type' => '平台',
            'app_version' => 'App 版本',
            'create_time' => 'Create Time',
            'save_path' => 'APK 文件',
            'download_url' => 'Download Url',
            'is_force' => '强制更新',
        ];
    }
}
