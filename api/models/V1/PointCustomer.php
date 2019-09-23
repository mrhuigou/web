<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%point_customer}}".
 *
 * @property integer $point_customer_id
 * @property integer $point_id
 * @property integer $customer_id
 * @property integer $points
 */
class PointCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%point_customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['point_id', 'customer_id', 'points'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'point_customer_id' => 'Point Customer ID',
            'point_id' => 'Point ID',
            'customer_id' => 'Customer ID',
            'points' => 'Points',
        ];
    }
    public function getPoint(){
        return $this->hasOne(Point::className(),['point_id'=>'point_id']);
    }
    public function getFlows(){
        return $this->hasMany(PointCustomerFlow::className(),['point_customer_id'=>'point_customer_id']);
    }
    public function getPoints(){
        //积分记录在家润系统
        return $this->points;
    }
}
