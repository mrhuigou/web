<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon_cate_to_coupon}}".
 *
 * @property integer $id
 * @property integer $cate_id
 * @property integer $coupon_id
 */
class CouponCateToCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_cate_to_coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cate_id', 'coupon_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cate_id' => 'Cate ID',
            'coupon_id' => 'Coupon ID',
        ];
    }
    public function getCoupon(){
        return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
}
