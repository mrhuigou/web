<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%promotion_detail_gift}}".
 *
 * @property integer $promotion_detail_gift_id
 * @property string $promotion_detail_gift_code
 * @property integer $promotion_detail_id
 * @property string $promotion_detail_code
 * @property string $promotion_type
 * @property integer $store_id
 * @property string $store_code
 * @property integer $product_id
 * @property string $product_code
 * @property string $type
 * @property integer $base_number
 * @property integer $quantity
 * @property string $price
 * @property string $old_price
 * @property integer $uplimit_quantity
 * @property integer $status
 * @property string $gift_type
 * @property integer $coupon_id
 * @property string $coupon_code
 */
class PromotionDetailGift extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%promotion_detail_gift}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promotion_detail_gift_code'], 'required'],
            [['promotion_detail_id', 'store_id', 'product_id', 'base_number', 'quantity', 'uplimit_quantity', 'status','coupon_id'], 'integer'],
            [['price', 'old_price'], 'number'],
            [['promotion_detail_gift_code', 'promotion_detail_code', 'store_code', 'product_code','gift_type'], 'string', 'max' => 255],
            [['promotion_type'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'promotion_detail_gift_id' => 'Promotion Detail Gift ID',
            'promotion_detail_gift_code' => 'Promotion Detail Gift Code',
            'promotion_detail_id' => 'promotion_detail_id OR promotion_group_id OR promotion_order_id',
            'promotion_detail_code' => 'Promotion Detail Code',
            'promotion_type' => '促销类型，单品促销=SINGLE、组合促销=GROUP、订单促销=ORDER',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'type' => '类型,倍数\\比率',
            'base_number' => '基数',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'old_price' => '原价',
            'uplimit_quantity' => '上限数量,赠品数量（0=赠送完毕）',
            'status' => '有效状态，0=无效，1=生效',
        ];
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
    public function getCoupon(){
        return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
}
