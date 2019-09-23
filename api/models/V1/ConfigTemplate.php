<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%config_template}}".
 *
 * @property integer $config_template_id
 * @property string $name
 * @property string $type
 * @property integer $status
 */
class ConfigTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['type'], 'string', 'max' => 96]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'config_template_id' => 'Config Template ID',
            'name' => 'Name',
            'type' => 'Type',
            'status' => 'Status',
        ];
    }
}
