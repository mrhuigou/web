<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_history}}".
 *
 * @property integer $order_history_id
 * @property integer $order_id
 * @property integer $order_status_id
 * @property integer $notify
 * @property string $comment
 * @property string $date_added
 */
class OrderHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_status_id', 'comment'], 'required'],
            [['order_id', 'order_status_id', 'notify'], 'integer'],
            [['comment'], 'string'],
            [['date_added'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_history_id' => 'Order History ID',
            'order_id' => '订单 ID',
            'order_status_id' => 'Order Status ID',
            'notify' => 'Notify',
            'comment' => '内容',
            'date_added' => '时间',
        ];
    }

    public function getOrderStatus(){
        return $this->hasOne(OrderStatus::className(),['order_status_id'=>'order_status_id']);
    }
}
