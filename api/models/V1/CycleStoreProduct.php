<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%cycle_store_product}}".
 *
 * @property integer $cycle_store_product_id
 * @property integer $cycle_store_id
 * @property string $product_code
 * @property integer $product_id
 * @property integer $quantity
 * @property string $store_code
 * @property integer $store_id
 * @property string $price
 * @property string $total
 */
class CycleStoreProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cycle_store_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cycle_store_id', 'product_id', 'quantity', 'store_id'], 'integer'],
            [['price', 'total'], 'number'],
            [['product_code', 'store_code'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cycle_store_product_id' => '该表是存储店铺设置的周期计划详情',
            'cycle_store_id' => 'Cycle Store ID',
            'product_code' => 'Product Code',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'store_code' => 'Store Code',
            'store_id' => 'Store ID',
            'price' => 'Price',
            'total' => 'Total',
        ];
    }
}
