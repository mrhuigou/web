<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%affiliate_plan_detail}}".
 *
 * @property integer $affiliate_plan_detail_id
 * @property string $code
 * @property string $plan_code
 * @property integer $affiliate_plan_id
 * @property string $product_code
 * @property string $pu_code
 * @property integer $price_type
 * @property string $price
 * @property string $affiliate_price
 * @property integer $max_buy_qty
 * @property integer $priority
 * @property string $image_url
 * @property integer $status
 */
class AffiliatePlanDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_plan_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['affiliate_plan_id', 'price_type', 'max_buy_qty', 'priority', 'status'], 'integer'],
            [['price', 'affiliate_price'], 'number'],
            [['code', 'plan_code', 'product_code', 'pu_code', 'image_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'affiliate_plan_detail_id' => 'Affiliate Plan Detail ID',
            'code' => 'Code',
            'plan_code' => 'Plan Code',
            'affiliate_plan_id' => 'Affiliate Plan ID',
            'product_code' => 'Product Code',
            'pu_code' => 'Pu Code',
            'price_type' => 'Price Type',
            'price' => 'Price',
            'affiliate_price' => 'Affiliate Price',
            'max_buy_qty' => 'Max Buy Qty',
            'priority' => 'Priority',
            'image_url' => 'Image Url',
            'status' => 'Status',
        ];
    }

    public function getProduct(){
        return $this->hasOne(Product::className(),['product_code'=>'product_code']);
    }

    public function getPrice(){
        return round($this->price_type == 1 ? $this->price:$this->product->productBase->price ,2);
    }

    //该商品加入购物车的数量
    public function getQty($affiliate_plan_id=0){
        $qty = 0;
        $params['affiliate_plan_id'] = $affiliate_plan_id;
        if(\Yii::$app->fxcart->hasPosition($this->product->getCartPositionFx($params)->getId())){
            $cart = \Yii::$app->fxcart->getPositions();
            $qty = $cart[$this->product->getCartPositionFx($params)->getId()]->getQuantity();
        }
        return $qty;
    }
}
