<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_events_vote}}".
 *
 * @property string $vote_id
 * @property integer $events_id
 * @property string $vote_title
 * @property integer $customer_id
 * @property string $created_at
 * @property string $end_time
 * @property integer $vote_count
 * @property integer $max_vote
 * @property integer $visible
 */
class ClubEventsVote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_events_vote}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['events_id', 'vote_title', 'customer_id', 'created_at', 'end_time'], 'required'],
            [['events_id', 'customer_id', 'vote_count', 'max_vote', 'visible'], 'integer'],
            [['created_at', 'end_time'], 'safe'],
            [['vote_title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vote_id' => 'Vote ID',
            'events_id' => 'Events ID',
            'vote_title' => 'Vote Title',
            'customer_id' => 'Customer ID',
            'created_at' => 'Created At',
            'end_time' => 'End Time',
            'vote_count' => 'Vote Count',
            'max_vote' => 'Max Vote',
            'visible' => 'Visible',
        ];
    }
}
