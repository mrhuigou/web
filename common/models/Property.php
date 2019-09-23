<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%property}}".
 *
 * @property integer $id
 * @property integer $property_group_id
 * @property string $code
 * @property string $name
 * @property integer $is_cname
 * @property integer $is_color
 * @property integer $is_list
 * @property integer $is_input
 * @property integer $is_key
 * @property integer $is_sku
 * @property integer $is_search
 * @property integer $is_must
 * @property integer $is_multi
 * @property integer $status
 * @property integer $sort_order
 * @property string $date_added
 *
 * @property ProductAttribute[] $productAttributes
 * @property PropertyGroup $propertyGroup
 * @property PropertyValue[] $propertyValues
 */
class Property extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%property}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_group_id', 'is_cname', 'is_color', 'is_list', 'is_input', 'is_key', 'is_sku', 'is_search', 'is_must', 'is_multi', 'status', 'sort_order'], 'integer'],
            [['date_added'], 'safe'],
            [['code', 'name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '属性ID',
            'property_group_id' => '属性组ID',
            'code' => '属性编码',
            'name' => '属性名称',
            'is_cname' => '是否允许别名',
            'is_color' => '是否颜色属性',
            'is_list' => '是否枚举属性',
            'is_input' => '是否输入属性',
            'is_key' => '是否关键属性',
            'is_sku' => '是否销售属性',
            'is_search' => '是否搜索属性',
            'is_must' => '是否必须',
            'is_multi' => '是否多选',
            'status' => '状态',
            'sort_order' => '排序',
            'date_added' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyGroup()
    {
        return $this->hasOne(PropertyGroup::className(), ['id' => 'property_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyValues()
    {
        return $this->hasMany(PropertyValue::className(), ['property_id' => 'id']);
    }
}
