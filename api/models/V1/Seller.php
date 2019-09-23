<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%seller}}".
 *
 * @property integer $id
 * @property string $device_type
 * @property string $user_id
 * @property string $channel_id
 * @property string $open_id
 * @property string $tag_id
 * @property string $date_added
 * @property string $date_modified
 */
class Seller extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seller}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['open_id', 'date_added', 'date_modified'], 'required'],
            [['date_added', 'date_modified'], 'safe'],
            [['device_type'], 'string', 'max' => 32],
            [['user_id', 'channel_id', 'open_id', 'tag_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_type' => 'Device Type',
            'user_id' => 'User ID',
            'channel_id' => 'Channel ID',
            'open_id' => 'Open ID',
            'tag_id' => 'Tag ID',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
