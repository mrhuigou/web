<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_cycle_detail}}".
 *
 * @property string $order_cycle_detail_id
 * @property integer $order_cycle_id
 * @property integer $order_id
 * @property integer $product_id
 * @property string $product_code
 * @property integer $quantity
 * @property string $price
 * @property string $shipping_time
 * @property string $comment
 * @property string $date_added
 * @property integer $status
 */
class OrderCycleDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_cycle_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_cycle_id', 'order_id', 'product_id', 'quantity', 'status'], 'integer'],
            [['order_id', 'product_id'], 'required'],
            [['price'], 'number'],
            [['shipping_time', 'date_added'], 'safe'],
            [['product_code'], 'string', 'max' => 20],
            [['comment'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_cycle_detail_id' => '商家周期购订单明细',
            'order_cycle_id' => 'Order Cycle ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'quantity' => '每期商品数量',
            'price' => '每期商品单价',
            'shipping_time' => '配送时间',
            'comment' => 'Comment',
            'date_added' => 'Date Added',
            'status' => '1正常，0终止，2延期',
        ];
    }
}
