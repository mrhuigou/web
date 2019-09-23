<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%exercise_history}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $exercise_id
 * @property integer $exercise_rule_id
 * @property integer $exercise_rule_coupon_id
 * @property integer $create_at
 */
class ExerciseHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exercise_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'exercise_id', 'exercise_rule_id', 'exercise_rule_coupon_id', 'create_at'], 'integer'],
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
            'exercise_id' => 'Exercise ID',
            'exercise_rule_id' => 'Exercise Rule ID',
            'exercise_rule_coupon_id' => 'Exercise Rule Coupon ID',
            'create_at' => 'Create At',
        ];
    }
}
