<?php

namespace api\models\V1;

use common\models\User;
use Yii;

/**
 * This is the model class for table "{{%lottery_result}}".
 *
 * @property integer $id
 * @property integer $lottery_id
 * @property integer $customer_id
 * @property integer $lottery_prize_id
 * @property integer $creat_at
 */
class LotteryResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lottery_result}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lottery_id', 'customer_id', 'lottery_prize_id', 'creat_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lottery_id' => 'Lottery ID',
            'customer_id' => 'Customer ID',
            'lottery_prize_id' => 'Lottery Prize ID',
            'creat_at' => 'Creat At',
        ];
    }
    public function getPrize(){
    	return $this->hasOne(LotteryPrize::className(),['id'=>'lottery_prize_id']);
    }
    public function getLottery(){
	    return $this->hasOne(Lottery::className(),['id'=>'lottery_id']);
    }
	public function getCustomer(){
		return $this->hasOne(User::className(),['customer_id'=>'customer_id']);
	}
}
