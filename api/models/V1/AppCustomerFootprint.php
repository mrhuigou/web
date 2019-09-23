<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%app_customer_footprint}}".
 *
 * @property integer $app_customer_footprint_id
 * @property integer $customer_id
 * @property integer $platform_id
 * @property string $platform_code
 * @property integer $product_id
 * @property string $product_code
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $store_id
 * @property string $store_code
 * @property string $type_id
 * @property string $device_type
 * @property string $channel_id
 * @property string $user_id
 * @property string $user_agent
 * @property string $date_added
 */
class AppCustomerFootprint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_customer_footprint}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'platform_id', 'product_id', 'date_added'], 'required'],
            [['customer_id', 'platform_id', 'product_id', 'product_base_id', 'store_id'], 'integer'],
            [['date_added'], 'safe'],
            [['platform_code', 'product_code', 'product_base_code', 'store_code'], 'string', 'max' => 40],
            [['type_id'], 'string', 'max' => 4],
            [['device_type', 'user_agent'], 'string', 'max' => 255],
            [['channel_id', 'user_id'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_customer_footprint_id' => 'App Customer Footprint ID',
            'customer_id' => 'Customer ID',
            'platform_id' => 'Platform ID',
            'platform_code' => 'Platform Code',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'type_id' => '1=店铺首页，2=商品详情',
            'device_type' => '移动设备系统类型',
            'channel_id' => '移动设备',
            'user_id' => '移动设备',
            'user_agent' => 'User Agent',
            'date_added' => '浏览时间',
        ];
    }
}
