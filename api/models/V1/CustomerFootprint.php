<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_footprint}}".
 *
 * @property integer $customer_footprint_id
 * @property integer $customer_id
 * @property integer $platform_id
 * @property string $platform_code
 * @property integer $product_id
 * @property string $product_code
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $store_id
 * @property string $store_code
 * @property string $foot_url
 * @property string $foot_host
 * @property string $ip
 * @property string $date_added
 */
class CustomerFootprint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_footprint}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'platform_id',  'date_added'], 'required'],
            [['customer_id', 'platform_id', 'product_id', 'product_base_id', 'store_id'], 'integer'],
            [['date_added'], 'safe'],
            [['platform_code', 'product_code', 'product_base_code', 'store_code'], 'string', 'max' => 40],
            [['foot_url'], 'string', 'max' => 255],
            [['foot_host', 'ip'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_footprint_id' => 'Customer Footprint ID',
            'customer_id' => 'Customer ID',
            'platform_id' => 'Platform ID',
            'platform_code' => 'Platform Code',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'foot_url' => '浏览URL',
            'foot_host' => 'Foot Host',
            'ip' => '客户ip',
            'date_added' => '浏览时间',
        ];
    }

    public function getProductBase(){
        return $this->hasOne(ProductBase::className(),['product_base_id'=>'product_base_id']);
    }
}
