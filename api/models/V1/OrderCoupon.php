<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_coupon}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $coupon_id
 * @property integer $status
 * @property string $date_added
 */
class OrderCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'coupon_id', 'status'], 'integer'],
            [['date_added'], 'safe'],
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
            'coupon_id' => 'Coupon ID',
            'status' => 'Status',
            'date_added' => 'Date Added',
        ];
    }
	public function getCoupon(){
		return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
	}
}
