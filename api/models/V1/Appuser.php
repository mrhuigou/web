<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%appuser}}".
 *
 * @property integer $appuser_id
 * @property string $appuser_name
 * @property string $appuser_code
 * @property string $appuser_key
 */
class Appuser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%appuser}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appuser_name', 'appuser_code', 'appuser_key'], 'required'],
            [['appuser_name', 'appuser_code', 'appuser_key'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'appuser_id' => 'Appuser ID',
            'appuser_name' => 'Appuser Name',
            'appuser_code' => 'Appuser Code',
            'appuser_key' => 'Appuser Key',
        ];
    }
}
