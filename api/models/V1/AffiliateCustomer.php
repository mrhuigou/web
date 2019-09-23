<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%affiliate_customer}}".
 *
 * @property integer $id
 * @property integer $affiliate_id
 * @property integer $customer_id
 * @property integer $creat_at
 */
class AffiliateCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['affiliate_id', 'customer_id', 'creat_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'affiliate_id' => 'Affiliate ID',
            'customer_id' => 'Customer ID',
            'creat_at' => 'Creat At',
        ];
    }
	public function getAffiliate(){
		return $this->hasOne(Affiliate::className(),['affiliate_id','affiliate_id']);
	}
	public function getCustomer(){
		return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
	}
}
