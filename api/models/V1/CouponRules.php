<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon_rules}}".
 *
 * @property integer $coupon_rules_id
 * @property integer $user_total_limit
 */
class CouponRules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_rules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'user_total_limit'], 'integer'],
            [['name','img_url'], 'string'],
            [[ 'start_time','end_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_rules_id' => 'Coupon Rules ID',
            'user_total_limit' => 'User Total Limit',
        ];
    }
    public function getDetails(){
        return $this->hasMany(CouponRulesDetail::className(),['coupon_rules_id'=>'coupon_rules_id']);
    }
}
