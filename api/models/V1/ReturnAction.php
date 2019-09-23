<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%return_action}}".
 *
 * @property integer $return_action_id
 * @property integer $language_id
 * @property string $name
 */
class ReturnAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%return_action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'name'], 'required'],
            [['language_id'], 'integer'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'return_action_id' => 'Return Action ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
        ];
    }
}
