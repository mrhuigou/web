<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property integer $option_id
 * @property string $code
 * @property string $type
 * @property integer $sort_order
 */
class Option extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['sort_order'], 'integer'],
            [['code', 'type'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'option_id' => 'Option ID',
            'code' => 'Code',
            'type' => 'Type',
            'sort_order' => 'Sort Order',
        ];
    }
}
