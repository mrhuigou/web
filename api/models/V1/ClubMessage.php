<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_message}}".
 *
 * @property integer $message_id
 * @property integer $from_customer_id
 * @property integer $from_group_id
 * @property integer $from_events_id
 * @property integer $to_customer_id
 * @property integer $is_read
 * @property string $title
 * @property string $content
 * @property string $post_time
 * @property integer $is_del
 * @property integer $is_system
 * @property integer $need_verify
 * @property integer $is_agree
 */
class ClubMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_customer_id', 'from_group_id', 'from_events_id', 'to_customer_id', 'is_read', 'is_del', 'is_system', 'need_verify', 'is_agree'], 'integer'],
            [['content'], 'string'],
            [['post_time'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'from_customer_id' => 'From Customer ID',
            'from_group_id' => 'From Group ID',
            'from_events_id' => 'From Events ID',
            'to_customer_id' => 'To Customer ID',
            'is_read' => 'Is Read',
            'title' => 'Title',
            'content' => 'Content',
            'post_time' => 'Post Time',
            'is_del' => 'Is Del',
            'is_system' => 'Is System',
            'need_verify' => 'Need Verify',
            'is_agree' => '0默认值表示未操作，1同意，-1拒绝',
        ];
    }
}
