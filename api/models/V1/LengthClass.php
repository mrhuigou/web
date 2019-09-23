<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%length_class}}".
 *
 * @property integer $length_class_id
 * @property string $value
 */
class LengthClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%length_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'length_class_id' => 'Length Class ID',
            'value' => 'Value',
        ];
    }
}
