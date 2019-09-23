<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%message_type}}".
 *
 * @property integer $message_type_id
 * @property string $name
 */
class MessageType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_type_id' => 'Message Type ID',
            'name' => 'Name',
        ];
    }
}
