<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%option_value_description}}".
 *
 * @property integer $option_value_id
 * @property string $option_value_code
 * @property integer $language_id
 * @property integer $option_id
 * @property string $name
 */
class OptionValueDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%option_value_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_value_id'], 'required'],
            [['option_value_id', 'language_id', 'option_id'], 'integer'],
            [['option_value_code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'option_value_id' => 'Option Value ID',
            'option_value_code' => 'Option Value Code',
            'language_id' => 'Language ID',
            'option_id' => 'Option ID',
            'name' => 'Name',
        ];
    }
}
