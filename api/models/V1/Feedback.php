<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property integer $feedback_id
 * @property integer $customer_id
 * @property integer $product_id
 * @property string $order_id
 * @property string $date_added
 * @property integer $status
 * @property string $name
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'product_id', 'status'], 'integer'],
            [['date_added'], 'safe'],
            [['order_id'], 'string', 'max' => 25],
            [['name'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'feedback_id' => 'Feedback ID',
            'customer_id' => 'Customer ID',
            'product_id' => 'Product ID',
            'order_id' => 'Order ID',
            'date_added' => 'Date Added',
            'status' => 'Status',
            'name' => 'Name',
        ];
    }
}
