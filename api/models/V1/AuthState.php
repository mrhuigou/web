<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%auth_state}}".
 *
 * @property integer $id
 * @property string $state
 * @property string $url
 * @property integer $created_at
 */
class AuthState extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_state}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'string'],
            [['created_at'], 'integer'],
            [['state'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state' => 'State',
            'url' => 'Url',
            'created_at' => 'Created At',
        ];
    }
}
