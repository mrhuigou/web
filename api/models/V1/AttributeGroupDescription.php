<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%attribute_group_description}}".
 *
 * @property integer $attribute_group_id
 * @property string $attribute_group_code
 * @property integer $language_id
 * @property string $name
 */
class AttributeGroupDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attribute_group_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_group_id', 'attribute_group_code', 'language_id', 'name'], 'required'],
            [['attribute_group_id', 'language_id'], 'integer'],
            [['attribute_group_code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attribute_group_id' => 'Attribute Group ID',
            'attribute_group_code' => 'Attribute Group Code',
            'language_id' => 'Language ID',
            'name' => 'Name',
        ];
    }
}
