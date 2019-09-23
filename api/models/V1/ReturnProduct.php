<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%return_product}}".
 *
 * @property integer $return_product_id
 * @property string $return_code
 * @property integer $return_id
 * @property integer $order_product_id
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $store_id
 * @property string $store_code
 * @property integer $product_id
 * @property string $product_code
 * @property string $model
 * @property string $name
 * @property string $sku_name
 * @property integer $quantity
 * @property string $total
 * @property string $product_total
 * @property string $unit
 * @property string $format
 * @property string $option
 * @property integer $opened
 * @property string $comment
 * @property integer $return_reason_id
 * @property integer $return_action_id
 * @property string $from_table
 * @property integer $from_id
 * @property string  $commission
 */
class ReturnProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%return_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['return_id', 'order_product_id','product_base_id', 'store_id', 'product_id', 'quantity', 'from_id'], 'integer'],
            [['total', 'product_total','commission'], 'number'],
            [['option', 'comment'], 'string'],
            [['return_code', 'product_base_code', 'store_code', 'product_code', 'unit', 'format', 'from_table'], 'string', 'max' => 125],
            [['model'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'return_product_id' => 'Return Product ID',
            'return_code' => 'Return Code',
            'return_id' => 'Return ID',
            'order_product_id'=>'order_product_id',
            'product_base_id' => '商品 Base ID',
            'product_base_code' => '商品 Base Code',
            'store_id' => '店铺 ID',
            'store_code' => '店铺 Code',
            'product_id' => '商品 ID',
            'product_code' => '商品 Code',
            'model' => 'Model',
            'name' => '名称',
            'sku_name' => 'sku_name',
            'quantity' => '数量',
            'total' => '退还现金',
            'product_total' => '商品金额',
            'unit' => '单位',
            'format' => '产品包装规格',
            'option' => 'Option',
	        'commission'=>'退还佣金'
        ];
    }
    public function getProduct(){
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }
    public function getOrderProduct(){
        return $this->hasOne(OrderProduct::className(),['order_product_id'=>'order_product_id']);
    }
    public function getReturnBase(){
        return $this->hasOne(ReturnBase::className(),['return_id'=>'return_id']);
    }
}
