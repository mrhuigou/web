<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_customer_event_type}}".
 *
 * @property integer $event_type_id
 * @property string $event_name
 */
class ClubCustomerEventType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_customer_event_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_name'], 'required'],
            [['event_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_type_id' => 'Event Type ID',
            'event_name' => 'Event Name',
        ];
    }
}
