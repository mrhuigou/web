<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_affiliate}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $username
 * @property string $telephone
 * @property string $commission
 * @property integer $status
 * @property string $date_added
 */
class CustomerAffiliate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_affiliate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'status'], 'integer'],
            [['commission'], 'number'],
            [['date_added'], 'safe'],
            [['username', 'telephone'], 'string', 'max' => 255],
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
            'username' => 'Username',
            'telephone' => 'Telephone',
            'commission' => 'Commission',
            'status' => 'Status',
            'date_added' => 'Date Added',
        ];
    }
    public function getCustomer(){
    	return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
}
