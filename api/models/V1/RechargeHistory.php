<?php

namespace api\models\V1;

use common\models\User;
use Yii;

/**
 * This is the model class for table "{{%recharge_history}}".
 *
 * @property string $id
 * @property integer $customer_id
 * @property integer $recharge_card_id
 * @property string $created_at
 * @property string $user_agent
 * @property string $recharge_card_info
 */
class RechargeHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%recharge_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'recharge_card_id'], 'required'],
            [['customer_id', 'recharge_card_id'], 'integer'],
            [['created_at'], 'safe'],
            [['user_agent', 'recharge_card_info'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'recharge_card_id' => 'Recharge Card ID',
            'created_at' => '充值时间',
            'user_agent' => 'User Agent',
            'recharge_card_info' => 'Recharge Card Info',
        ];
    }
    public function getRechargeCard(){
        return $this->hasOne(RechargeCard::className(),['id'=>'recharge_card_id']);
    }
    public function getCustomer(){
        return $this->hasOne(User::className(),['customer_id'=>'customer_id']);
    }
}
