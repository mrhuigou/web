<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $category_id
 * @property integer $attribute_group_id
 * @property string $code
 * @property string $image
 * @property string $parentcode
 * @property integer $parent_id
 * @property integer $top
 * @property integer $column
 * @property integer $sort_order
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 * @property integer $industry_id
 * @property string $industry_code
 * @property string $stock_type
 * @property string $low_limit
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
            [['attribute_group_id', 'parent_id', 'top', 'column', 'sort_order', 'status', 'industry_id'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['code', 'parentcode', 'industry_code'], 'string', 'max' => 32],
            [['image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'attribute_group_id' => '该分类下产品使用哪个属性组,用于商品对比',
            'code' => 'Code',
            'image' => 'Image',
            'parentcode' => 'Parentcode',
            'parent_id' => 'Parent ID',
            'top' => 'Top',
            'column' => 'Column',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'industry_id' => '行业id',
            'industry_code' => '行业code',
        ];
    }
    public function getDescription(){
        return $this->hasOne(CategoryDescription::className(),['category_id'=>'category_id']);
    }
    public function getChildren(){
        return $this->hasMany(Category::className(),['parent_id'=>'category_id']);
    }
}
