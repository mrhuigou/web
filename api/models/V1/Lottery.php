<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%lottery}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $start_time
 * @property string $end_time
 */
class Lottery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lottery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['chances_per_customer'],'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '活动ID',
            'title' => '活动标题',
            'description' => '活动描述',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
        ];
    }
	public function getUser(){
		return $this->hasMany(LotteryCustomer::className(),['lottery_id'=>'id']);
	}
	public function getLotteryPrize(){
        return $this->hasMany(LotteryPrize::className(),['lottery_id'=>'id']);
    }
}
