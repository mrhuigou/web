<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_events_type}}".
 *
 * @property string $events_type_id
 * @property string $events_type_name
 */
class ClubEventsType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_events_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['events_type_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'events_type_id' => 'Events Type ID',
            'events_type_name' => 'Events Type Name',
        ];
    }
}
