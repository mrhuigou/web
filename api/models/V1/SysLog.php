<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%sys_log}}".
 *
 * @property integer $log_id
 * @property integer $user_id
 * @property string $user_name
 * @property string $server_host
 * @property string $request_url
 * @property string $module
 * @property string $ip
 * @property string $datetime
 */
class SysLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sys_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['datetime'], 'safe'],
            [['user_name', 'request_url', 'module'], 'string', 'max' => 255],
            [['server_host'], 'string', 'max' => 400],
            [['ip'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'server_host' => 'Server Host',
            'request_url' => 'Request Url',
            'module' => 'Module',
            'ip' => 'Ip',
            'datetime' => 'Datetime',
        ];
    }
}
