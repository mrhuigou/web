<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_cycle}}".
 *
 * @property integer $order_cycle_id
 * @property integer $order_id
 * @property integer $cycle_id
 * @property integer $cycle_store_id
 * @property string $shipping_time
 * @property integer $order_status_id
 * @property integer $status
 * @property integer $delay_from_id
 * @property string $date_added
 * @property string $every
 * @property integer $periods
 * @property integer $sent_to_erp
 * @property string $sent_time
 */
class OrderCycle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_cycle}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'cycle_id', 'cycle_store_id', 'order_status_id', 'status', 'delay_from_id', 'periods', 'sent_to_erp'], 'integer'],
            [['shipping_time', 'date_added', 'sent_time'], 'safe'],
            [['every'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_cycle_id' => 'Order Cycle ID',
            'order_id' => 'Order ID',
            'cycle_id' => '用户自定义周期id',
            'cycle_store_id' => '商家自定义周期id',
            'shipping_time' => 'Shipping Time',
            'order_status_id' => 'Order Status ID',
            'status' => 'Status',
            'delay_from_id' => 'Delay From ID',
            'date_added' => 'Date Added',
            'every' => 'Every',
            'periods' => 'Periods',
            'sent_to_erp' => '0,没有同步，1，代表同步',
            'sent_time' => 'Sent Time',
        ];
    }
}
