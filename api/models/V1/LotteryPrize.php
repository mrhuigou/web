<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%lottery_prize}}".
 *
 * @property integer $id
 * @property integer $lottery_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property integer $quantity
 * @property integer $probability
 * @property integer $coupon_id
 * @property double $angle
 */
class LotteryPrize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lottery_prize}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lottery_id', 'quantity', 'probability','coupon_id'], 'integer'],
            [['description'], 'string'],
            [['angle'], 'number'],
            [['title','image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '奖项ID',
            'lottery_id' => '活动id',
            'title' => '奖项名称',
            'description' => '奖项描述',
	        'image'=>'奖项图',
            'quantity' => '基础量（参加人数大于等于该值）',
            'probability' => '出现概率',
            'angle' => '偏转角度/排序值',
	        'coupon_id'=>'折扣券ID'
        ];
    }
    public function getResult(){
		return $this->hasMany(LotteryResult::className(),['lottery_prize_id'=>'id']);
    }
    public function getCoupon(){
		return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
}
