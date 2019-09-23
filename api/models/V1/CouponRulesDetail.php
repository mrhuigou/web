<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon_rules_detail}}".
 *
 * @property integer $coupon_rules_detail_id
 * @property integer $coupon_rules_id
 * @property integer $coupon_id
 */
class CouponRulesDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_rules_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [[ 'coupon_rules_id', 'coupon_id'], 'integer'],
            [['name','img_url'], 'string'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_rules_detail_id' => 'Coupon Rules Detail ID',
            'coupon_rules_id' => 'Coupon Rules ID',
            'coupon_id' => 'Coupon ID',
            'img_url' =>'展示图片',
            'name' =>'展示名称'
        ];
    }
    public function getCoupon(){
        return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
    public function getCouponRules(){
        return $this->hasOne(CouponRules::className(),['coupon_rules_id'=>'coupon_rules_id']);
    }
}
