<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $parent_id
 * @property integer $sort_order
 * @property integer $property_group_id
 * @property integer $industory_id
 * @property integer $status
 *
 * @property Brand[] $brands
 * @property Industory $industory
 * @property PropertyGroup $propertyGroup
 * @property Product[] $products
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort_order', 'property_group_id', 'industory_id', 'status'], 'integer'],
            [['code', 'name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '类目ID',
            'code' => '类目编码',
            'name' => '类目名称',
            'parent_id' => '类目父ID',
            'sort_order' => '排序ID',
            'property_group_id' => '属性组ID',
            'industory_id' => 'Industory ID',
            'status' => '状态',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrands()
    {
        return $this->hasMany(Brand::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustory()
    {
        return $this->hasOne(Industory::className(), ['id' => 'industory_id']);
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
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }
}
