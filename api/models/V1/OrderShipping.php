<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_shipping}}".
 *
 * @property integer $order_shipping_id
 * @property integer $order_id
 * @property string $shipping_firstname
 * @property string $shipping_telephone
 * @property string $shipping_gender
 * @property string $shipping_postcode
 * @property string $shipping_address_1
 * @property string $shipping_country
 * @property integer $shipping_country_id
 * @property string $shipping_zone_code
 * @property string $shipping_zone
 * @property integer $shipping_zone_id
 * @property string $shipping_country_code
 * @property string $shipping_city
 * @property integer $shipping_city_id
 * @property string $shipping_city_code
 * @property string $shipping_district
 * @property integer $shipping_district_id
 * @property string $shipping_district_code
 * @property string $shipping_address_format
 * @property string $shipping_method
 * @property string $shipping_code
 * @property string $lat
 * @property string $lng
 * @property string $delivery
 * @property string $delivery_code
 * @property string $delivery_time
 * @property integer $post_type
 * @property string $station_code
 * @property integer $station_id
 * @property integer $is_delivery
 */
class OrderShipping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_shipping}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'station_id', 'shipping_zone_id', 'shipping_city_id', 'shipping_district_id', 'is_delivery'], 'integer'],
            [['delivery','delivery_time'], 'string'],
            [['shipping_firstname', 'station_code','shipping_zone_code', 'shipping_city_code', 'shipping_district_code', 'shipping_code', 'delivery_code'], 'string', 'max' => 40],
            [['shipping_telephone', 'shipping_zone', 'shipping_city', 'shipping_district', 'shipping_method', 'delivery'], 'string', 'max' => 125],
            [['shipping_gender'], 'string', 'max' => 32],
            [['shipping_postcode'], 'string', 'max' => 10],
            [['shipping_address_1'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_shipping_id' => 'Order Shipping ID',
            'order_id' => '订单 ID',
            'shipping_firstname' => '收货人姓名',
            'shipping_telephone' => '收货人电话',
            'shipping_gender' => '性别',
            'shipping_postcode' => '邮编',
            'shipping_address_1' => '收货地址',
            'shipping_zone' => '省级',
            'shipping_zone_id' => '省级ID',
            'shipping_zone_code' => '省级编码',
            'shipping_city' => '市级',
            'shipping_city_id' => '市级ID',
            'shipping_city_code' => '市级编码',
            'shipping_district' => '行政区',
            'shipping_district_id' => '区级ID',
            'shipping_district_code' => '区级编码',
            'shipping_method' => '配送类型',
            'shipping_code' => '配送类型编码',
            'delivery_code' => '配送编码',
            'delivery' => '配送日期',
            'delivery_time'=>'配送时间',
        ];
    }
}
