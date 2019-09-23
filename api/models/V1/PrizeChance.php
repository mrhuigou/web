<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%prize_chance}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $expiration_time
 * @property integer $order_id
 * @property integer $status
 * @property string $date_added
 */
class PrizeChance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prize_chance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_id', 'status'], 'integer'],
            [['expiration_time', 'date_added'], 'safe'],
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
            'expiration_time' => 'Expiration Time',
            'order_id' => 'Order ID',
            'status' => 'Status',
            'date_added' => 'Date Added',
        ];
    }
}
