<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%option_value}}".
 *
 * @property integer $option_value_id
 * @property string $option_value_code
 * @property integer $option_id
 * @property string $image
 * @property integer $sort_order
 */
class OptionValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%option_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_value_code', 'option_id'], 'required'],
            [['option_id', 'sort_order'], 'integer'],
            [['option_value_code'], 'string', 'max' => 32],
            [['image'], 'string', 'max' => 255]
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
            'option_id' => 'Option ID',
            'image' => 'Image',
            'sort_order' => 'Sort Order',
        ];
    }
}
