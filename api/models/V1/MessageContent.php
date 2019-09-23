<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%message_content}}".
 *
 * @property integer $message_content_id
 * @property string $type
 * @property string $image
 * @property string $title
 * @property string $description
 * @property string $item_id
 * @property string $link
 * @property string $date_added
 * @property string filter_date_begin
 *  * @property string filter_date_end
 */
class MessageContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'],'required'],
            [['description'], 'string'],
            [['date_added','filter_date_begin','filter_date_end'], 'safe'],
            [['type','device'], 'string', 'max' => 30],
            [['title', 'item_id','filter_type','filter_textfield'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_content_id' => 'Message Content ID',
            'type' => 'Type',
            'device' => 'Device',
            'title' => 'Title',
            'description' => 'Description',
            'item_id' => 'Item ID',
            'date_added' => 'Date Added',
            'filter_type' => 'filter Type',
            'filter_textfield' => 'filter Textfield',
            'filter_date_begin' => 'Filter_begin',
            'filter_date_end' => 'Filter_end',
        ];
    }
}
