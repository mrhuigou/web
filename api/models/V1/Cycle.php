<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%cycle}}".
 *
 * @property integer $cycle_id
 * @property integer $customer_id
 * @property string $cycle_name
 * @property string $shipping_name
 * @property string $shipping_city_id
 * @property string $shipping_district_id
 * @property string $shipping_address
 * @property string $shipping_telephone
 * @property string $invoice_name
 * @property string $every
 * @property string $every_value
 * @property string $first_shipping_date
 * @property string $next_shipping_date
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $periods
 * @property string $from_table
 * @property integer $from_id
 */
class Cycle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cycle}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'shipping_city_id', 'shipping_district_id', 'status', 'periods', 'from_id'], 'integer'],
            [['first_shipping_date', 'next_shipping_date', 'created_at', 'updated_at'], 'safe'],
            [['cycle_name', 'shipping_name', 'shipping_address', 'invoice_name', 'every_value'], 'string', 'max' => 255],
            [['shipping_telephone'], 'string', 'max' => 12],
            [['every'], 'string', 'max' => 10],
            [['from_table'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cycle_id' => 'Cycle ID',
            'customer_id' => 'Customer ID',
            'cycle_name' => 'Cycle Name',
            'shipping_name' => 'Shipping Name',
            'shipping_city_id' => 'Shipping City ID',
            'shipping_district_id' => 'Shipping District ID',
            'shipping_address' => 'Shipping Address',
            'shipping_telephone' => 'Shipping Telephone',
            'invoice_name' => 'Invoice Name',
            'every' => 'Every',
            'every_value' => 'Every Value',
            'first_shipping_date' => 'First Shipping Date',
            'next_shipping_date' => 'Next Shipping Date',
            'status' => '0暂停，1执行',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'periods' => '期数 用户自定义周期默认为1期',
            'from_table' => '来源表，customer 或者 store',
            'from_id' => '来源id，来源用户id 还是店铺id ,与from_table组合才有意义',
        ];
    }
}
