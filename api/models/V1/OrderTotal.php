<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_total}}".
 *
 * @property integer $order_total_id
 * @property integer $order_id
 * @property string $code
 * @property string $title
 * @property string $text
 * @property string $value
 * @property integer $sort_order
 * @property integer $code_id
 * @property integer $customer_code_id
 */
class OrderTotal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_total}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'code', 'title', 'text', 'sort_order'], 'required'],
            [['order_id', 'sort_order', 'code_id', 'customer_code_id'], 'integer'],
            [['value'], 'number'],
            [['code'], 'string', 'max' => 32],
            [['title', 'text'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_total_id' => 'Order Total ID',
            'order_id' => 'Order ID',
            'code' => 'Code',
            'title' => 'Title',
            'text' => 'Text',
            'value' => 'Value',
            'sort_order' => 'Sort Order',
            'code_id' => 'coupon_id,voucher_id,promotion_id',
            'customer_code_id' => 'ustomer_coupon_id\\customer_voucher_id\\promotion_order_id',
            'remark'=>'备注',
        ];
    }
    public function getCoupon(){
        return $this->hasOne(Coupon::className(),['coupon_id'=>'code_id']);
    }
    public function getPromotion(){
        return $this->hasOne(Promotion::className(),['promotion_id'=>'code_id']);
    }

    public function getRemark(){
        if($this->code=='coupon'){
            if($model=$this->getCoupon()->one()){
                return $model->name.'['.$model->code."]";
            }

        }elseif($this->code=='order'){
            if($model=$this->getPromotion()->one()){
                return $model->name.'['.$model->code."]";
            }

        }
    }
}
