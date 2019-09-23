<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon_gift}}".
 *
 * @property integer $coupon_gift_id
 * @property integer $coupon_id
 * @property integer $product_id
 * @property integer $qty
 * @property integer $status
 */
class CouponGift extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_gift}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'product_id', 'qty', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_gift_id' => 'Coupon Gift ID',
            'coupon_id' => 'Coupon ID',
            'product_id' => 'Product ID',
            'qty' => 'Qty',
            'status' => 'Status',
        ];
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
}
