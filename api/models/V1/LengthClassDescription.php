<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%length_class_description}}".
 *
 * @property integer $length_class_id
 * @property integer $language_id
 * @property string $title
 * @property string $unit
 */
class LengthClassDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%length_class_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'title', 'unit'], 'required'],
            [['language_id'], 'integer'],
            [['title'], 'string', 'max' => 32],
            [['unit'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'length_class_id' => 'Length Class ID',
            'language_id' => 'Language ID',
            'title' => 'Title',
            'unit' => 'Unit',
        ];
    }
}
