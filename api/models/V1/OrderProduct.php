<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_product}}".
 *
 * @property integer $order_product_id
 * @property integer $order_id
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $product_id
 * @property string $product_code
 * @property string $model
 * @property string $name
 * @property string $sku_name
 * @property string $quantity
 * @property string $price
 * @property string $total
 * @property string $tax
 * @property string $unit
 * @property string $format
 * @property integer $refund_qty
 * @property string $pay_total
 * @property integer $promotion_id
 * @property integer $promotion_detail_id
 * @property integer $reward
 * @property string $remark
 * @property string $commission
 */
class OrderProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_base_id', 'product_id', 'refund_qty', 'promotion_id','promotion_detail_id', 'reward'], 'integer'],
            [['quantity', 'price', 'total', 'tax', 'pay_total','commission'], 'number'],
            [['remark'], 'string'],
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
            'order_product_id' => 'Order Product ID',
            'order_id' => 'Order ID',
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
            'pay_total' => 'Pay Total',
            'promotion_id' => 'Promotion ID',
            'promotion_detail_id' => 'Promotion Detail ID',
            'reward' => 'Reward',
            'remark' => 'Remark',
	        'commission'=>'佣金'
        ];
    }
    public function getSku(){
        $sku_option=[];
        $product=Product::findOne(['product_id'=>$this->product_id]);
        if($product) {
            if ($product->sku) {
                $sku = explode(";", $product->sku);
                if ($sku) {
                    foreach ($sku as $sku_value) {
                        list($option_id, $option_value) = explode(':', $sku_value);
                        $option_name = OptionDescription::findOne(['option_id' => $option_id])->name;
                        $option_value_name = OptionValueDescription::findOne(['option_value_id' => $option_value])->name;
                        $sku_option[] = $option_name.":".$option_value_name;
                    }
                }
            } else {
                $sku_option[] = "包装/规格:".($product->format ? $product->unit . "(" . $product->format . ")" : $product->unit);
            }
        }
        return $sku_option;
    }
    public function getOrder(){
          return $this->hasOne(Order::className(),['order_id'=>'order_id']);
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
    public function getReview(){
          return $this->hasOne(Review::className(),['order_product_id'=>'order_product_id']);
    }
    public function getGift(){
        return $this->hasMany(OrderGift::className(),['order_product_id'=>'order_product_id']);
    }
    public function getPromotion(){
        return $this->hasOne(Promotion::className(),['promotion_id'=>'promotion_id']);
    }
    public function getPromotionDetail(){
        return $this->hasOne(PromotionDetail::className(),['promotion_detail_id'=>'promotion_detail_id']);
    }
    public function getRefundQty(){
        $qty=0;
        if($models=ReturnProduct::find()->where(['from_table'=>'order_product','from_id'=>$this->order_product_id])->all()){
            foreach($models as $model){
                if(!in_array($model->returnBase->return_status_id,[6])){
                    $qty+=$model->quantity;
                }
            }
        }
        return $qty;
    }
}
