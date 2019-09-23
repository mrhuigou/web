<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%ground_push_point_to_customer}}".
 *
 * @property integer $point_to_customer_id
 * @property integer $point_id
 * @property integer $customer_id
 * @property integer $order_id
 */
class GroundPushPointToCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ground_push_point_to_customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['point_id', 'customer_id', 'order_id'], 'required'],
            [['point_id', 'customer_id', 'order_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'point_to_customer_id' => 'Point To Customer ID',
            'point_id' => 'Point ID',
            'customer_id' => 'Customer ID',
            'order_id' => 'Order ID',
        ];
    }
    public function getOrder(){
        return $this->hasOne(Order::className(),['order_id'=>'order_id']);
    }
    public function getPoint(){
        return $this->hasOne(GroundPushPoint::className(),['id'=>'point_id']);
    }
}