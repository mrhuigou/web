<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%express_order}}".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $order_code
 * @property string $order_type
 * @property integer $customer_id
 * @property string $contact_name
 * @property string $telephone
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $delivery_type
 * @property string $delivery_date
 * @property string $delivery_time
 * @property string $total
 * @property string $remark
 * @property integer $express_status_id
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $send_status
 * @property integer $send_time
 */
class ExpressOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%express_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id','customer_id', 'express_status_id', 'create_at', 'update_at', 'send_status', 'send_time'], 'integer'],
            [['address','remark'], 'string'],
            [['total'], 'number'],
            [['order_code', 'order_type', 'contact_name', 'telephone', 'city', 'district', 'delivery_type', 'delivery_date', 'delivery_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
	        'company_id'=>'Company Id',
            'order_code' => 'Order Code',
            'order_type' => 'Order Type',
            'customer_id' => 'Customer ID',
            'contact_name' => 'Contact Name',
            'telephone' => 'Telephone',
            'city' => 'City',
            'district' => 'District',
            'address' => 'Address',
            'delivery_type' => 'Delivery Type',
            'delivery_date' => 'Delivery Date',
            'delivery_time' => 'Delivery Time',
            'total' => 'Total',
	        'remark'=>'Remark',
            'express_status_id' => 'Express Status ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'send_status' => 'Send Status',
            'send_time' => 'Send Time',
        ];
    }
    public function getStatus(){
    	return $this->hasOne(ExpressStatus::className(),['id'=>'express_status_id']);
    }
    public function getExpressOrderProducts(){
    	return $this->hasMany(ExpressOrderProduct::className(),['order_id'=>'id']);
    }
    public function getCompany(){
    	return $this->hasOne(LegalPerson::className(),['legal_person_id'=>'company_id']);
    }
}
