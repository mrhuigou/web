<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property integer $message_id
 * @property integer $message_type_id
 * @property string $content
 * @property integer $customer_id
 * @property integer $is_read
 * @property string $date_added
 * @property string $date_read
 * @property integer $status
 * @property integer $message_content_id
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_type_id'], 'required'],
            [['message_type_id','message_content_id', 'customer_id', 'is_read', 'status'], 'integer'],
            [['date_added', 'date_read'], 'safe'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'message_type_id' => 'Message Type ID',
            'content' => 'Content',
            'customer_id' => 'Customer ID',
            'is_read' => 'Is Read',
            'message_content_id'=>'Message Content ID',
            'date_added' => 'Date Added',
            'date_read' => 'Date Read',
            'status' => 'Status',
        ];
    }
    public function getMessageContent(){
        return $this->hasOne(MessageContent::className(),['message_content_id'=>'message_content_id']);
    }
}
