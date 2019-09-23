<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%push_message}}".
 *
 * @property integer $message_id
 * @property integer $customer_id
 * @property integer $range_type
 * @property string $send_time
 * @property string $date_added
 * @property string $type
 * @property string $value
 * @property string $title
 * @property string $description
 * @property string $image
 * @property integer $app_customer_id
 */
class PushMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%push_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id'], 'required'],
            [['message_id', 'customer_id', 'range_type', 'app_customer_id'], 'integer'],
            [['send_time', 'date_added'], 'safe'],
            [['type'], 'string', 'max' => 20],
            [['value', 'title', 'description', 'image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'customer_id' => 'Customer ID',
            'range_type' => '0：tag组播 1：广播 2：批量单播 3：组合运算 4：精准推送 5：LBS推送 6：系统预留 7：单播',
            'send_time' => 'Send Time',
            'date_added' => 'Date Added',
            'type' => 'Type',
            'value' => 'Value',
            'title' => 'Title',
            'description' => 'Description',
            'image' => 'Image',
            'app_customer_id' => 'App Customer ID',
        ];
    }
}
