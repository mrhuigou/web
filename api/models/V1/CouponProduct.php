<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon_product}}".
 *
 * @property integer $coupon_product_id
 * @property integer $coupon_id
 * @property integer $product_id
 * @property integer $status
 */
class CouponProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'product_id','status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_product_id' => 'Coupon Product ID',
            'coupon_id' => 'Coupon ID',
            'product_id' => 'Product ID',
            'status'=>'状态'
        ];
    }

    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
    public function getCoupon(){
        return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
}
