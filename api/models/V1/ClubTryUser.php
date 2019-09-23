<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_try_user}}".
 *
 * @property integer $id
 * @property integer $try_id
 * @property integer $customer_id
 * @property string $shipping_name
 * @property string $shipping_telephone
 * @property integer $zone_id
 * @property integer $city_id
 * @property integer $district_id
 * @property string $address
 * @property string $postcode
 * @property string $creat_at
 * @property integer $order_id
 * @property integer $status
 */
class ClubTryUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_try_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'try_id', 'customer_id', 'zone_id', 'city_id', 'district_id', 'order_id', 'status'], 'integer'],
            [['creat_at'], 'safe'],
            [['shipping_name', 'address'], 'string', 'max' => 255],
            [['shipping_telephone'], 'string', 'max' => 32],
            [['postcode'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'try_id' => 'Try ID',
            'customer_id' => '用户 ID',
            'shipping_name' => '收货人姓名',
            'shipping_telephone' => '收货电话',
            'zone_id' => 'Zone ID',
            'city_id' => 'City ID',
            'district_id' => 'District ID',
            'address' => '收货地址',
            'postcode' => '邮编',
            'creat_at' => '创建时间',
            'order_id' => '订单 ID',
            'begin_date' => '开始时间',
            'end_date' =>'结束时间',
            'status' => '状态',
        ];
    }
    public function getTry(){
        return $this->hasOne(ClubTry::className(),['id'=>'try_id']);
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }

    public function getDistrict(){
        return $this->hasOne(District::className(),['district_id'=>'district_id']);
    }
}
