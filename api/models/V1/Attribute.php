<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%attribute}}".
 *
 * @property integer $attribute_id
 * @property string $attribute_code
 * @property integer $is_search
 * @property integer $attribute_group_id
 * @property string $attribute_group_code
 * @property integer $sort_order
 */
class Attribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_code', 'attribute_group_id', 'attribute_group_code'], 'required'],
            [['is_search', 'attribute_group_id', 'sort_order'], 'integer'],
            [['attribute_code', 'attribute_group_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attribute_id' => 'Attribute ID',
            'attribute_code' => 'Attribute Code',
            'is_search' => 'Is Search',
            'attribute_group_id' => 'Attribute Group ID',
            'attribute_group_code' => 'Attribute Group Code',
            'sort_order' => 'Sort Order',
        ];
    }
    public function getDescription(){
        return $this->hasOne(AttributeDescription::className(),['attribute_id'=>'attribute_id']);
    }
}
