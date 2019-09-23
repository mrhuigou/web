<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_collect}}".
 *
 * @property integer $customer_collect_id
 * @property integer $customer_id
 * @property integer $platform_id
 * @property string $platform_code
 * @property integer $store_id
 * @property string $store_code
 * @property integer $product_id
 * @property string $product_code
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $type_id
 * @property string $date_added
 */
class CustomerCollect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_collect}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'platform_id', 'store_id', 'product_id', 'product_base_id', 'type_id'], 'integer'],
            [['platform_id'], 'required'],
            [['date_added'], 'safe'],
            [['platform_code', 'store_code', 'product_code', 'product_base_code'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_collect_id' => 'Customer Collect ID',
            'customer_id' => 'Customer ID',
            'platform_id' => 'Platform ID',
            'platform_code' => 'Platform Code',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
            'type_id' => '收藏类型，1=商品，2=店铺',
            'date_added' => 'Date Added',
        ];
    }

    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
    public function getProductbase(){
        return $this->hasOne(ProductBase::className(),['product_base_id'=>'product_base_id']);
    }
    public function getStore(){
        return $this->hasOne(Store::className(),['store_id'=>'store_id']);
    }

}
