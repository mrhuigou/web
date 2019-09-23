<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%ground_push_plan_view}}".
 *
 * @property integer $id
 * @property string $code
 * @property integer $ground_push_plan_id
 * @property string $product_code
 * @property string $price
 * @property integer $max_buy_qty
 * @property integer $sort_order
 * @property integer $status
 */
class GroundPushPlanView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ground_push_plan_view}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ground_push_plan_id', 'max_buy_qty', 'sort_order', 'status'], 'integer'],
            [['price'], 'number'],
            [['code', 'product_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '地推明细code',
            'ground_push_plan_id' => '所属地推计划',
            'product_code' => 'Product Code',
            'price' => '地推价格',
            'max_buy_qty' => '最大购买量',
            'sort_order' => '排序',
            'status' => '状态',
        ];
    }
    public function getProduct(){
    	return $this->hasOne(Product::className(),['product_code'=>'product_code']);
    }
    public function getStock(){
        return GroundPushStock::findOne(['product_code'=>$this->product_code,'ground_push_point_id'=>$this->plan->ground_push_point_id]);
    	//return $this->hasOne(GroundPushStock::className(),['product_code'=>'product_code'])->where(['']);
    }
    public function getPlan(){
        return $this->hasOne(GroundPushPlan::className(),['id'=>'ground_push_plan_id']);
    }
}
