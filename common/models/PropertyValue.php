<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%property_value}}".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $code
 * @property string $name
 * @property integer $status
 * @property integer $sort_order
 * @property string $date_added
 *
 * @property ProductAttribute[] $productAttributes
 * @property Property $property
 */
class PropertyValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%property_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id', 'status', 'sort_order'], 'integer'],
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
            'id' => '属性值ID',
            'property_id' => '属性表_属性ID',
            'code' => '属性值编码',
            'name' => '属性值名称',
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
        return $this->hasMany(ProductAttribute::className(), ['property_value_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'property_id']);
    }
}
