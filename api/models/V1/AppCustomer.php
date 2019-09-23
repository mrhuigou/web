<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%app_customer}}".
 *
 * @property integer $app_customer_id
 * @property string $device_type
 * @property string $channel_id
 * @property string $user_id
 * @property integer $open_id
 * @property string $tag_id
 * @property string $date_added
 * @property string $date_modified
 */
class AppCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['open_id'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['device_type', 'channel_id', 'user_id', 'tag_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_customer_id' => 'App Customer ID',
            'device_type' => '移动设备系统类型',
            'channel_id' => '移动设备',
            'user_id' => '移动设备',
            'open_id' => '用户ID',
            'tag_id' => 'Tag ID',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
