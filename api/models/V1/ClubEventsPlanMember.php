<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_events_plan_member}}".
 *
 * @property integer $events_plan_id
 * @property integer $customer_id
 */
class ClubEventsPlanMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_events_plan_member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['events_plan_id', 'customer_id'], 'required'],
            [['events_plan_id', 'customer_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'events_plan_id' => 'Events Plan ID',
            'customer_id' => 'Customer ID',
        ];
    }
}
