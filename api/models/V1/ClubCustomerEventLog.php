<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_customer_event_log}}".
 *
 * @property integer $event_id
 * @property integer $customer_id
 * @property integer $event_type_id
 * @property integer $type_name_id
 * @property integer $content_id
 * @property string $create_time
 * @property integer $is_del
 */
class ClubCustomerEventLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_customer_event_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'event_type_id', 'type_name_id', 'content_id', 'create_time'], 'required'],
            [['customer_id', 'event_type_id', 'type_name_id', 'content_id', 'is_del'], 'integer'],
            [['create_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_id' => 'Event ID',
            'customer_id' => 'Customer ID',
            'event_type_id' => 'Event Type ID',
            'type_name_id' => 'Type Name ID',
            'content_id' => 'Content ID',
            'create_time' => 'Create Time',
            'is_del' => 'Is Del',
        ];
    }
}
