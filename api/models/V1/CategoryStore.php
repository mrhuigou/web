<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_store}}".
 *
 * @property string $category_store_id
 * @property string $category_store_code
 * @property integer $store_id
 * @property string $store_code
 * @property integer $parent_id
 * @property string $parent_code
 * @property string $name
 * @property integer $sort_order
 * @property string $meta_description
 * @property string $meta_keyword
 * @property string $description
 * @property string $date_added
 * @property string $date_modified
 * @property integer $status
 */
class CategoryStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_store}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'parent_id', 'sort_order', 'status'], 'integer'],
            [['description'], 'string'],
            [['date_added', 'date_modified'], 'safe'],
            [['category_store_code', 'store_code', 'parent_code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 255],
            [['meta_description', 'meta_keyword'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_store_id' => 'Category Store ID',
            'category_store_code' => 'Category Store Code',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'parent_id' => 'Parent ID',
            'parent_code' => 'Parent Code',
            'name' => 'Name',
            'sort_order' => 'Sort Order',
            'meta_description' => '店铺简介',
            'meta_keyword' => 'Meta Keyword',
            'description' => 'Description',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'status' => 'Status',
        ];
    }
}
