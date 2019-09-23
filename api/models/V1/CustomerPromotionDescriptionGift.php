<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_promotion_description_gift}}".
 *
 * @property integer $id
 * @property integer $customer_promotion_description_id
 * @property integer $coupon_id
 */
class CustomerPromotionDescriptionGift extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_promotion_description_gift}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_promotion_description_id', 'coupon_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_promotion_description_id' => 'Customer Promotion Description ID',
            'coupon_id' => 'Coupon ID',
        ];
    }
    public function getCoupon(){
    	return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
}
