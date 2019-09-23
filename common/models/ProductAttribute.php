<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%product_attribute}}".
 *
 * @property integer $id
 * @property integer $property_value_id
 * @property integer $property_id
 * @property integer $product_id
 * @property string $name
 * @property string $image
 *
 * @property PropertyValue $propertyValue
 * @property Property $property
 * @property Product $product
 */
class ProductAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_value_id', 'property_id', 'product_id'], 'integer'],
            [['name', 'image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '产品属性ID',
            'property_value_id' => '属性值表_属性值ID',
            'property_id' => '属性表_属性ID',
            'product_id' => '产品表_产品ID',
            'name' => '属性值自定义',
            'image' => '图片地址',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyValue()
    {
        return $this->hasOne(PropertyValue::className(), ['id' => 'property_value_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'property_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
