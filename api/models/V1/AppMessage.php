<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%app_message}}".
 *
 * @property integer $id
 * @property string $body
 * @property integer $creat_at
 */
class AppMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body'], 'string'],
            [['creat_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'body' => 'Body',
            'creat_at' => 'Creat At',
        ];
    }
}
