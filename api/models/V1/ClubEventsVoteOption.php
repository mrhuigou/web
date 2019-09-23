<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_events_vote_option}}".
 *
 * @property string $option_id
 * @property string $option_name
 * @property string $option_description
 * @property integer $option_vote_count
 */
class ClubEventsVoteOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_events_vote_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_name'], 'required'],
            [['option_vote_count'], 'integer'],
            [['option_name', 'option_description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'option_id' => 'Option ID',
            'option_name' => 'Option Name',
            'option_description' => 'Option Description',
            'option_vote_count' => 'Option Vote Count',
        ];
    }
}
