<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%attribute_group}}".
 *
 * @property integer $attribute_group_id
 * @property string $attribute_group_code
 * @property integer $sort_order
 */
class AttributeGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attribute_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_group_code'], 'required'],
            [['sort_order'], 'integer'],
            [['attribute_group_code'], 'string', 'max' => 32]
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
            'sort_order' => 'Sort Order',
        ];
    }
}
