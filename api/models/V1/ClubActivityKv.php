<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_activity_kv}}".
 *
 * @property integer $club_activity_kv_id
 * @property integer $activity_id
 * @property string $key
 * @property string $value
 * @property string $type
 * @property integer $is_require
 */
class ClubActivityKv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_activity_kv}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['club_activity_kv_id', 'activity_id', 'is_require'], 'integer'],
            [['key', 'value'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'club_activity_kv_id' => 'Club Activity Kv ID',
            'activity_id' => 'Activity ID',
            'key' => 'Key',
            'value' => 'Value',
            'type' => 'Type',
            'is_require' => 'Is Require',
        ];
    }
}
