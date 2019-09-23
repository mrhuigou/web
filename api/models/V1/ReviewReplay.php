<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%review_replay}}".
 *
 * @property string $review_replay_id
 * @property integer $review_id
 * @property integer $server_user_id
 * @property string $text
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 */
class ReviewReplay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%review_replay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['review_id', 'server_user_id', 'status'], 'integer'],
            [['text'], 'string'],
            [['date_added', 'date_modified'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'review_replay_id' => 'Review Replay ID',
            'review_id' => 'Review ID',
            'server_user_id' => 'Server User ID',
            'text' => 'Text',
            'status' => 'Status',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
