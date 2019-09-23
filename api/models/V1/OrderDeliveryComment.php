<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_delivery_comment}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $order_id
 * @property string $comment
 * @property integer $score
 * @property string $tags
 * @property integer $created_at
 * @property integer $send_status
 */
class OrderDeliveryComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_delivery_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_id', 'score', 'created_at', 'send_status'], 'integer'],
            [['comment', 'tags'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'order_id' => 'Order ID',
            'comment' => 'Comment',
            'score' => 'Score',
            'tags' => 'Tags',
            'created_at' => 'Created At',
            'send_status' => 'Send Status',
        ];
    }
}
