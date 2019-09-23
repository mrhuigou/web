<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_scan}}".
 *
 * @property integer $order_scan_id
 * @property integer $order_id
 * @property string $from_table
 * @property integer $from_table_id
 * @property string $scan_type
 * @property string $scan_data
 * @property string $scan_key
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 * @property string $expire_date
 */
class OrderScan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_scan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'from_table', 'from_table_id', 'scan_data', 'scan_key', 'date_added', 'date_modified', 'expire_date'], 'required'],
            [['order_id', 'from_table_id', 'status'], 'integer'],
            [['scan_data'], 'string'],
            [['date_added', 'date_modified', 'expire_date'], 'safe'],
            [['from_table'], 'string', 'max' => 255],
            [['scan_type'], 'string', 'max' => 32],
            [['scan_key'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_scan_id' => 'Order Scan ID',
            'order_id' => 'Order ID',
            'from_table' => 'From Table',
            'from_table_id' => 'From Table ID',
            'scan_type' => 'NONE= 无,ORDER=按订单,PRODUCT=按品 ,QUANTITY=按数量',
            'scan_data' => 'Scan Data',
            'scan_key' => 'Scan Key',
            'status' => '0,未验证，1代表验证',
            'date_added' => 'Date Added',
            'date_modified' => 'SD',
            'expire_date' => 'Expire Date',
        ];
    }
    public function getOrder(){
        return $this->hasOne(Order::className(),['order_id'=>'order_id']);
    }
}
