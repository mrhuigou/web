<?php

namespace api\models\V1;

use common\models\User;
use Yii;

/**
 * This is the model class for table "{{%lottery_customer}}".
 *
 * @property integer $id
 * @property integer $lottery_id
 * @property integer $customer_id
 * @property integer $creat_at
 */
class LotteryCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lottery_customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lottery_id', 'customer_id', 'creat_at'], 'integer'],
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
            'creat_at' => 'Creat At',
        ];
    }
    public function getCustomer(){
    	return $this->hasOne(User::className(),['customer_id'=>'customer_id']);
    }
}
