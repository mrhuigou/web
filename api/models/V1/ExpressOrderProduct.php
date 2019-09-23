<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%express_order_product}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $shop_code
 * @property string $product_base_code
 * @property string $product_code
 * @property string $product_name
 * @property integer $quantity
 * @property string $description
 */
class ExpressOrderProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%express_order_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'quantity'], 'integer'],
            [['description'], 'string'],
            [['shop_code', 'product_base_code', 'product_code', 'product_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'shop_code' => 'Shop Code',
            'product_base_code' => 'Product Base Code',
            'product_code' => 'Product Code',
            'product_name' => 'Product Name',
            'quantity' => 'Quantity',
            'description' => 'Description',
        ];
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_code'=>'product_code']);
    }
}
