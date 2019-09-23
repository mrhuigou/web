<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "jr_order_gift".
 *
 * @property integer $order_gift_id
 * @property integer $order_id
 * @property integer $order_product_id
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $product_id
 * @property string $product_code
 * @property string $model
 * @property string $name
 * @property string $sku_name
 * @property integer $quantity
 * @property string $price
 * @property string $total
 * @property string $tax
 * @property string $unit
 * @property string $format
 * @property integer $refund_qty
 * @property integer $promotion_id
 * @property integer $promotion_detail_id
 */
class OrderGift extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jr_order_gift';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_product_id', 'product_base_id', 'product_id', 'quantity', 'refund_qty', 'promotion_id','promotion_detail_id'], 'integer'],
            [['price', 'total', 'tax'], 'number'],
            [['product_base_code', 'product_code'], 'string', 'max' => 40],
            [['model'], 'string', 'max' => 64],
            [['name', 'sku_name'], 'string', 'max' => 255],
            [['unit', 'format'], 'string', 'max' => 125],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_gift_id' => 'Order Gift ID',
            'order_id' => 'Order ID',
            'order_product_id' => 'Order Product ID',
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'model' => 'Model',
            'name' => 'Name',
            'sku_name' => 'Sku Name',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'total' => 'Total',
            'tax' => 'Tax',
            'unit' => 'Unit',
            'format' => 'Format',
            'refund_qty' => 'Refund Qty',
            'promotion_id' => '促销ID',
            'promotion_detail_id' => '促销明细ID',
        ];
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id' => 'product_id']);
    }
    public function getPromotionOrderDetail(){
        return $this->hasOne(PromotionOrder::className(),['promotion_order_id'=>'promotion_id']);
    }
    public function getPromotionDetail(){
        return $this->hasOne(PromotionDetail::className(),['promotion_detail_id'=>'promotion_id']);
    }
    public function getRefundQty(){
        $qty=0;
        if($models=ReturnProduct::find()->where(['from_table'=>'order_gift','from_id'=>$this->order_gift_id])->all()){
            foreach($models as $model){
                if(!in_array($model->returnBase->return_status_id,[6])){
                    $qty+=$model->quantity;
                }
            }
        }
        return $qty;
    }
}
