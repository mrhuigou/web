<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%extension}}".
 *
 * @property integer $extension_id
 * @property string $type
 * @property string $code
 */
class Extension extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%extension}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'code'], 'required'],
            [['type', 'code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'extension_id' => 'Extension ID',
            'type' => 'Type',
            'code' => 'Code',
        ];
    }
}
