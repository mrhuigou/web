<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%layouts}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $max_modules
 * @property string $supported_width
 * @property integer $is_must_dispay
 * @property integer $status
 */
class Layouts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%layouts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['max_modules', 'is_must_dispay', 'status'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['name', 'supported_width'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'max_modules' => 'Max Modules',
            'supported_width' => 'Supported Width',
            'is_must_dispay' => 'Is Must Dispay',
            'status' => 'Status',
        ];
    }
}
