<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%setting}}".
 *
 * @property integer $setting_id
 * @property integer $platform_id
 * @property string $group
 * @property string $key
 * @property string $value
 * @property integer $serialized
 * @property integer $store_id
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['platform_id', 'serialized', 'store_id'], 'integer'],
            [['group', 'key', 'value', 'serialized'], 'required'],
            [['value'], 'string'],
            [['group'], 'string', 'max' => 32],
            [['key'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setting_id' => 'Setting ID',
            'platform_id' => 'Platform ID',
            'group' => 'Group',
            'key' => 'Key',
            'value' => 'Value',
            'serialized' => 'Serialized',
            'store_id' => 'Store ID',
        ];
    }
}
