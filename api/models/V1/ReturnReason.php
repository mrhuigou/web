<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%return_reason}}".
 *
 * @property integer $return_reason_id
 * @property string $name
 */
class ReturnReason extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%return_reason}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'return_reason_id' => 'Return Reason ID',
            'name' => 'Name',
        ];
    }
}
