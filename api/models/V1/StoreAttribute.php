<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_attribute}}".
 *
 * @property integer $store_attribute_id
 * @property integer $attribute_id
 * @property string $attribute_code
 * @property integer $store_id
 * @property string $store_code
 * @property integer $language_id
 * @property string $text
 */
class StoreAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'store_id'], 'required'],
            [['attribute_id', 'store_id', 'language_id'], 'integer'],
            [['text'], 'string'],
            [['attribute_code', 'store_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_attribute_id' => 'Store Attribute ID',
            'attribute_id' => 'Attribute ID',
            'attribute_code' => 'Attribute Code',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'language_id' => 'Language ID',
            'text' => 'Text',
        ];
    }
}
