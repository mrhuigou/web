<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_coupon}}".
 *
 * @property integer $customer_coupon_id
 * @property integer $customer_id
 * @property integer $coupon_id
 * @property string $description
 * @property integer $is_use
 * @property string $start_time
 * @property string $end_time
 * @property string $date_added
 * @property string $date_used
 * @property integer $from_hongbao_id
 */
class CustomerCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'coupon_id'], 'required'],
            [['customer_id', 'coupon_id', 'is_use','from_hongbao_id'], 'integer'],
            [['description'], 'string'],
            [['start_time','end_time','date_added', 'date_used'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_coupon_id' => '编号',
            'customer_id' => 'Customer ID',
            'coupon_id' => '优惠券编号',
            'description' => '说明',
            'start_time'=>'开始时间',
            'end_time'=>'结束时间',
            'is_use' => '状态',
            'date_added' => '获得时间',
            'date_used' => '使用时间',
        ];
    }
    public function getCoupon(){
        return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
}
