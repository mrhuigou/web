<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%exercise_rule}}".
 *
 * @property integer $id
 * @property integer $exercise_id
 * @property integer $is_subcription
 * @property integer $order_days
 * @property integer $order_count
 * @property string $order_total
 * @property string $product_datas
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 */
class ExerciseRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exercise_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exercise_id', 'is_subcription', 'order_days', 'order_count', 'status'], 'integer'],
            [['order_total'], 'number'],
            [['product_datas'], 'string'],
            [['start_time', 'end_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '规则编号',
            'exercise_id' => '活动编号',
            'is_subcription' => '是否关注',
            'order_days' => '天数',
            'order_count' => '订单数',
            'order_total' => '累计订单额',
            'product_datas' => '商品包装',
            'start_time' => '开始',
            'end_time' => '结束',
            'status' => '状态',
        ];
    }
    public function getExercise(){
    	return $this->hasOne(Exercise::className(),['id'=>'exercise_id']);
    }
    public function getCoupon(){
    	return $this->hasMany(ExerciseRuleCoupon::className(),['exercise_rule_id'=>'id']);
    }
}
