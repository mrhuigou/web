<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_reward}}".
 *
 * @property integer $customer_reward_id
 * @property integer $customer_id
 * @property integer $order_id
 * @property string $order_no
 * @property string $description
 * @property integer $points
 * @property string $date_added
 * @property integer $type_id
 * @property string $date_start
 * @property string $date_end
 */
class CustomerReward extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_reward}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_id', 'points', 'type_id'], 'integer'],
            [['description'], 'required'],
            [['description'], 'string'],
            [['date_added', 'date_start', 'date_end'], 'safe'],
            [['order_no'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_reward_id' => 'Customer Reward ID',
            'customer_id' => 'Customer ID',
            'order_id' => 'Order ID',
            'order_no' => 'Order No',
            'description' => 'Description',
            'points' => 'Points',
            'date_added' => 'Date Added',
            'type_id' => '积分类型,1=普通类型',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
        ];
    }
}
