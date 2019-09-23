<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_product_limit}}".
 *
 * @property integer $customer_product_limit_id
 * @property integer $customer_id
 * @property string $product_discount_code
 * @property string $product_code
 * @property string $product_pucode
 * @property integer $order_id
 * @property string $date_added
 * @property integer $limit_status
 * @property string $ip
 */
class CustomerProductLimit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_product_limit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'product_discount_code', 'product_code', 'product_pucode', 'order_id', 'date_added'], 'required'],
            [['customer_id', 'order_id', 'limit_status'], 'integer'],
            [['date_added'], 'safe'],
            [['product_discount_code', 'product_code', 'product_pucode'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_product_limit_id' => 'Customer Product Limit ID',
            'customer_id' => 'Customer ID',
            'product_discount_code' => 'Product Discount Code',
            'product_code' => 'Product Code',
            'product_pucode' => 'Product Pucode',
            'order_id' => 'Order ID',
            'date_added' => 'Date Added',
            'limit_status' => '1为占用，2为已经使用',
            'ip' => 'Ip',
        ];
    }
}
