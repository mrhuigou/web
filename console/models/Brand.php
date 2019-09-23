<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%brand}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $code
 * @property string $name
 * @property string $cname
 * @property string $description
 * @property string $image
 * @property string $url
 * @property integer $status
 * @property integer $sort_order
 * @property string $date_added
 *
 * @property Category $category
 * @property Product[] $products
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'status', 'sort_order'], 'integer'],
            [['description'], 'string'],
            [['date_added'], 'safe'],
            [['code', 'name', 'cname'], 'string', 'max' => 50],
            [['image', 'url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '品牌ID',
            'category_id' => '类目表_类目ID',
            'code' => '品牌编码',
            'name' => '品牌名',
            'cname' => '品牌别名',
            'description' => '品牌描述',
            'image' => '品牌LOGO',
            'url' => '品牌官方地址',
            'status' => '状态',
            'sort_order' => '排序',
            'date_added' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id']);
    }
}
