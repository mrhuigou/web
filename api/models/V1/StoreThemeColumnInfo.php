<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_theme_column_info}}".
 *
 * @property integer $store_theme_column_info_id
 * @property string $store_theme_column_info_code
 * @property integer $store_theme_column_id
 * @property string $theme_column_type
 * @property string $title
 * @property string $contents
 * @property integer $sort
 * @property string $image
 * @property string $url
 * @property integer $product_id
 * @property string $product_code
 * @property integer $status
 */
class StoreThemeColumnInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_theme_column_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_theme_column_id', 'sort', 'product_id', 'status'], 'integer'],
            [['contents','title'], 'string'],
            [['store_theme_column_info_code', 'theme_column_type', 'product_code'], 'string', 'max' => 32],
            [['image', 'url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_theme_column_info_id' => 'Store Theme Column Info ID',
            'store_theme_column_info_code' => 'Store Theme Column Info Code',
            'store_theme_column_id' => 'Store Theme Column ID',
            'theme_column_type' => 'Theme Column Type',
            'title' => 'Title',
            'contents' => 'Contents',
            'sort' => 'Sort',
            'image' => 'Image',
            'url' => 'Url',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'status' => 'Status',
        ];
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
}
