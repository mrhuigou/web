<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%config_template_description}}".
 *
 * @property integer $config_template_id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 * @property integer $serialized
 */
class ConfigTemplateDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config_template_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_template_id', 'language_id', 'description'], 'required'],
            [['config_template_id', 'language_id', 'serialized'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'config_template_id' => 'Config Template ID',
            'language_id' => 'Language ID',
            'title' => 'Title',
            'description' => 'Description',
            'serialized' => 'Serialized',
        ];
    }
}
