<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%return_status}}".
 *
 * @property integer $return_status_id
 * @property integer $language_id
 * @property string $name
 * @property string $code
 */
class ReturnStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%return_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'name'], 'required'],
            [['language_id'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['code'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'return_status_id' => 'Return Status ID',
            'language_id' => 'Language ID',
            'name' => '状态',
            'code' => 'Code',
        ];
    }
}
