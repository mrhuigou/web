<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%exercise_rule_coupon}}".
 *
 * @property integer $id
 * @property integer $exercise_rule_id
 * @property integer $coupon_id
 * @property integer $probability
 * @property integer $share_status
 */
class ExerciseRuleCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exercise_rule_coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exercise_rule_id', 'coupon_id', 'probability','share_status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'exercise_rule_id' => '规则编号',
            'coupon_id' => '优惠券编号',
            'probability' => '概率',
	        'share_status'=>'分享状态'
        ];
    }
    public function getCoupon(){
    	return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
}
