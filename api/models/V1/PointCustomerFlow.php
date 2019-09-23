<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%point_customer_flow}}".
 *
 * @property integer $point_customer_flow_id
 * @property integer $point_customer_id
 * @property integer $customer_id
 * @property string $description
 * @property integer $amount
 * @property integer $points
 * @property string $remark
 * @property string $date_added
 * @property string $type
 * @property integer $type_id
 */
class PointCustomerFlow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%point_customer_flow}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['point_customer_flow_id'], 'required'],
            [['point_customer_flow_id', 'point_customer_id', 'customer_id', 'amount', 'points', 'type_id'], 'integer'],
            [['remark'], 'string'],
            [['date_added'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'point_customer_flow_id' => 'Point Customer Flow ID',
            'point_customer_id' => 'Point Customer ID',
            'customer_id' => 'Customer ID',
            'description' => 'Description',
            'amount' => 'Amount',
            'points' => 'Points',
            'remark' => 'Remark',
            'date_added' => 'Date Added',
            'type' => 'Type',
            'type_id' => 'Type ID',
        ];
    }
    public function getPointCustomer(){
        return $this->hasOne(PointCustomer::className(),['point_customer_id'=>'point_customer_id']);
    }
}
