<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_hongbao}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $order_id
 * @property integer $status
 * @property string $name
 * @property string $amount
 * @property integer $create_at
 * @property integer $update_at
 * @property string $split_amount
 */
class CustomerHongbao extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_hongbao}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_id', 'status', 'create_at', 'update_at'], 'integer'],
            [['name','split_amount'],'string'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name'=>'name',
            'customer_id' => 'Customer ID',
            'order_id' => 'Order ID',
            'status' => 'Status',
            'amount' => 'Account',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'split_amount'=>'split_amount',
        ];
    }
    public function getHistory(){
        return $this->hasMany(CustomerHongbaoHistroy::className(),['customer_hongbao_id'=>'id']);
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
}
