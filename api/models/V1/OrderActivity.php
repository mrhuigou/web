<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_activity}}".
 *
 * @property integer $order_activity_id
 * @property integer $order_id
 * @property integer $activity_id
 * @property string $activity_name
 * @property integer $activity_item_id
 * @property string $activity_item_name
 * @property integer $quantity
 * @property string $price
 * @property string $total
 */
class OrderActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'activity_id', 'activity_item_id', 'quantity'], 'integer'],
            [['price', 'total'], 'number'],
            [['activity_name', 'activity_item_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_activity_id' => 'Order Activity ID',
            'order_id' => 'Order ID',
            'activity_id' => 'Activity ID',
            'activity_name' => 'Activity Name',
            'activity_item_id' => 'Activity Item ID',
            'activity_item_name' => 'Activity Item Name',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'total' => 'Total',
        ];
    }
    public function getOption(){
        return $this->hasMany(OrderActivityOption::className(),['order_activity_id'=>'order_activity_id']);
    }
    public function getOrder(){
        return $this->hasOne(Order::className(),['order_id'=>'order_id']);
    }
    public function getActivity(){
        return $this->hasOne(ClubActivity::className(),['id'=>'activity_id']);
    }
}
