<?php

namespace api\models\V1;
use Yii;

/**
 * This is the model class for table "{{%customer_chest}}".
 *
 * @property string $customer_chest_id
 * @property integer $customer_treasure_id
 * @property integer $customer_id
 * @property integer $product_id
 * @property string $product_code
 * @property string $product_option
 * @property integer $product_type
 * @property integer $status
 * @property string $date_added
 * @property integer $order_id
 * @property string $order_no
 * @property string $date_expired
 * @property integer $is_online
 */
class CustomerChest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_chest}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_treasure_id', 'customer_id', 'product_id', 'product_type', 'status', 'order_id', 'is_online'], 'integer'],
            [['product_option'], 'string'],
            [['date_added', 'date_expired'], 'safe'],
            [['date_expired'], 'required'],
            [['product_code'], 'string', 'max' => 10],
            [['order_no'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_chest_id' => 'Customer Chest ID',
            'customer_treasure_id' => 'Customer Treasure ID',
            'customer_id' => 'Customer ID',
            'product_id' => 'Product ID',
            'product_code' => '商品编码',
            'product_option' => 'Product Option',
            'product_type' => 'Product Type',
            'status' => '0表示未领用，1表示占用，2表示已领',
            'date_added' => '添加时间',
            'order_id' => '订单 ID',
            'order_no' => 'Order No',
            'date_expired' => '过期时间',
            'is_online' => '是否在线领取，0，为线下领取，1为线上领取',
        ];
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }

    public function getTreasure(){
        return $this->hasOne(Treasure::className(),['treasure_id'=>'customer_treasure_id']);
    }
}
