<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon_card}}".
 *
 * @property integer $id
 * @property integer $coupon_id
 * @property string $card_code
 * @property string $card_pwd
 * @property integer $status
 */
class CouponCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'status'], 'integer'],
            [['card_code', 'card_pwd'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'coupon_id' => 'Coupon ID',
            'card_code' => 'Card Code',
            'card_pwd' => 'Card Pwd',
            'status' => 'Status',
        ];
    }
    public function getHistory(){
        return $this->hasMany(CouponCardHistory::className(),['coupon_card_id'=>'id']);
    }
    public function getCoupon(){
        return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
}
