<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon_history}}".
 *
 * @property integer $coupon_history_id
 * @property integer $coupon_id
 * @property integer $order_id
 * @property integer $customer_id
 * @property string $amount
 * @property string $date_added
 */
class CouponHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'order_id', 'customer_id', 'date_added'], 'required'],
            [['coupon_id', 'order_id', 'customer_id'], 'integer'],
            [['date_added'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_history_id' => 'Coupon History ID',
            'coupon_id' => 'Coupon ID',
            'order_id' => 'Order ID',
            'customer_id' => 'Customer ID',
            'amount' => 'Amount',
            'date_added' => 'Date Added',
        ];
    }
    public function getCoupon(){
        return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
}
