<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%weight_class_description}}".
 *
 * @property integer $weight_class_id
 * @property integer $language_id
 * @property string $title
 * @property string $unit
 */
class WeightClassDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weight_class_description}}';
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
            'weight_class_id' => 'Weight Class ID',
            'language_id' => 'Language ID',
            'title' => 'Title',
            'unit' => 'Unit',
        ];
    }
}
