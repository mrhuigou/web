<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%promotion_history}}".
 *
 * @property integer $promotion_history_id
 * @property string $promotion_type
 * @property integer $promotion_id
 * @property integer $promotion_detail_id
 * @property integer $order_id
 * @property integer $customer_id
 * @property integer $quantity
 * @property integer $status
 * @property string $date_added
 */
class PromotionHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%promotion_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promotion_id', 'promotion_detail_id', 'order_id', 'customer_id','quantity', 'status'], 'integer'],
            [['date_added'], 'safe'],
            [['promotion_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'promotion_history_id' => 'Promotion History ID',
            'promotion_type' => 'Promotion Type',
            'promotion_id' => 'Promotion ID',
            'promotion_detail_id' => 'Promotion Detail ID',
            'order_id' => 'Order ID',
            'customer_id' => 'Customer ID',
            'quantity'=>'Quantity',
            'status' => 'Status',
            'date_added' => 'Date Added',
        ];
    }
}
