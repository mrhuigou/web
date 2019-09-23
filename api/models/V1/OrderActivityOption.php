<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_activity_option}}".
 *
 * @property integer $order_activity_option_id
 * @property integer $order_id
 * @property integer $order_activity_id
 * @property integer $activity_option_id
 * @property string $activity_option_name
 * @property integer $activity_option_value_id
 * @property string $activity_option_value
 */
class OrderActivityOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_activity_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_activity_id', 'activity_option_id', 'activity_option_value_id'], 'integer'],
            [['activity_option_name', 'activity_option_value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_activity_option_id' => 'Order Activity Option ID',
            'order_id' => 'Order ID',
            'order_activity_id' => 'Order Activity ID',
            'activity_option_id' => 'Activity Option ID',
            'activity_option_name' => 'Activity Option Name',
            'activity_option_value_id' => 'Activity Option Value ID',
            'activity_option_value' => 'Activity Option Value',
        ];
    }
}
