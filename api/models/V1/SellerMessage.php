<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%seller_message}}".
 *
 * @property integer $message_id
 * @property string $message_type
 * @property string $message_source
 * @property string $title
 * @property string $content
 * @property string $open_id
 * @property integer $status
 * @property string $data_added
 * @property string $data_modify
 */
class SellerMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seller_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['status'], 'integer'],
            [['data_added', 'data_modify'], 'safe'],
            [['message_type', 'message_source'], 'string', 'max' => 32],
            [['title', 'open_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'message_type' => 'Message Type',
            'message_source' => 'Message Source',
            'title' => 'Title',
            'content' => 'Content',
            'open_id' => 'Open ID',
            'status' => '是否已读',
            'data_added' => 'Data Added',
            'data_modify' => 'Data Modify',
        ];
    }
}
