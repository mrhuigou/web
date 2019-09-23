<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_events_plan}}".
 *
 * @property string $events_plan_id
 * @property integer $events_id
 * @property string $created_at
 * @property integer $is_top
 * @property integer $is_closed
 */
class ClubEventsPlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_events_plan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['events_id', 'created_at'], 'required'],
            [['events_id', 'is_top', 'is_closed'], 'integer'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'events_plan_id' => 'Events Plan ID',
            'events_id' => 'Events ID',
            'created_at' => 'Created At',
            'is_top' => 'Is Top',
            'is_closed' => 'Is Closed',
        ];
    }
}
