<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%weight_class}}".
 *
 * @property integer $weight_class_id
 * @property string $value
 */
class WeightClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weight_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'weight_class_id' => 'Weight Class ID',
            'value' => 'Value',
        ];
    }
}
