<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%option_description}}".
 *
 * @property integer $option_id
 * @property string $code
 * @property integer $language_id
 * @property string $name
 * @property string $group_name
 */
class OptionDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%option_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_id', 'name'], 'required'],
            [['option_id', 'language_id'], 'integer'],
            [['code'], 'string', 'max' => 32],
            [['name', 'group_name'], 'string', 'max' => 128]
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
            'language_id' => 'Language ID',
            'name' => 'Name',
            'group_name' => 'Group Name',
        ];
    }
}
