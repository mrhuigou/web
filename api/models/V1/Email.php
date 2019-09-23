<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%email}}".
 *
 * @property integer $id
 * @property string $to
 * @property string $subject
 * @property string $message
 * @property string $file_path
 * @property string $file_option
 * @property integer $status
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'file_option'], 'string'],
            [['status'], 'integer'],
            [['to', 'subject', 'file_path'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'to' => 'To',
            'subject' => 'Subject',
            'message' => 'Message',
            'file_path' => 'File Path',
            'file_option' => 'File Option',
            'status' => 'Status',
        ];
    }
}
