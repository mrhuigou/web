<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%recharge_card}}".
 *
 * @property string $id
 * @property string $card_no
 * @property integer $value
 * @property string $card_code
 * @property string $card_pin
 * @property string $start_time
 * @property string $end_time
 * @property string $created_at
 * @property integer $status
 * @property integer $title
 */
class RechargeCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%recharge_card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'card_code', 'card_pin', 'start_time', 'status', 'title'], 'required'],
            [['status'], 'integer'],
            [['value'], 'number'],
            [['start_time', 'end_time', 'created_at'], 'safe'],
            [['card_code', 'card_pin'], 'string', 'max' => 32],
            ['card_no', 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_no'=>'卡号',
            'value' => '金额',
            'card_code' => '编码',
            'card_pin' => '密码',
            'start_time' => '开始时间',
            'end_time' => '过期时间',
            'created_at' => '生成时间',
            'status' => '状态',
            'title' => '名称',
        ];
    }
    public function getRechargeHistory(){
        return $this->hasOne(RechargeHistory::className(),['recharge_card_id'=>'id']);
    }

}
