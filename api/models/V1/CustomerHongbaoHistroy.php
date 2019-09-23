<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_hongbao_histroy}}".
 *
 * @property integer $id
 * @property integer $customer_hongbao_id
 * @property integer $customer_id
 * @property integer $create_at
 * @property string $amount
 */
class CustomerHongbaoHistroy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_hongbao_histroy}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_hongbao_id', 'customer_id', 'create_at'], 'integer'],
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
            'customer_hongbao_id' => 'Customer Hongbao ID',
            'customer_id' => 'Customer ID',
            'create_at' => 'Create At',
            'amount' => 'Account',
        ];
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
    public function getHongbao(){
        return $this->hasOne(CustomerHongbao::className(),['id'=>'customer_hongbao_id']);
    }
}
