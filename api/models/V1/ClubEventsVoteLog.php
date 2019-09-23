<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_events_vote_log}}".
 *
 * @property string $vote_log_id
 * @property integer $vote_id
 * @property integer $customer_id
 * @property string $vote_option_id_array
 * @property string $created_at
 */
class ClubEventsVoteLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_events_vote_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vote_id', 'customer_id'], 'integer'],
            [['customer_id', 'created_at'], 'required'],
            [['created_at'], 'safe'],
            [['vote_option_id_array'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vote_log_id' => 'Vote Log ID',
            'vote_id' => 'Vote ID',
            'customer_id' => 'Customer ID',
            'vote_option_id_array' => 'Vote Option Id Array',
            'created_at' => 'Created At',
        ];
    }
}
