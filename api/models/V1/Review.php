<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%review}}".
 *
 * @property integer $review_id
 * @property integer $customer_id
 * @property string $author
 * @property string $text
 * @property integer $rating
 * @property integer $service
 * @property integer $delivery
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 * @property integer $product_id
 * @property string $product_code
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $store_id
 * @property string $store_code
 * @property integer $order_id
 * @property integer $order_product_id
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%review}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'rating', 'service', 'delivery', 'status', 'product_id', 'product_base_id', 'store_id', 'order_id'], 'integer'],
            [['text'], 'string'],
            [['date_added', 'date_modified'], 'safe'],
            [['author'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'review_id' => 'Review ID',
            'customer_id' => '用户ID',
            'author' => '用户名',
            'text' => '评论内容',
            'rating' => '商品',
            'service' => '服务',
            'delivery' => '配送',
            'status' => '状态',
            'date_added' => '创建时间',
            'date_modified' => '修改时间',
            'product_id' => '商品包装ID',
            'product_code' => '包装编码',
            'product_base_id' => '商品ID',
            'product_base_code' => '商品编码',
            'store_id' => '店铺ID',
            'store_code' => '店铺编码',
            'order_id' => '订单号',
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

    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
    public function getOrderProduct(){
        return $this->hasOne(OrderProduct::className(),['order_id'=>'order_id','order_product_id'=>'order_product_id']);
    }
}
