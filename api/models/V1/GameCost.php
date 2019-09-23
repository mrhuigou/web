<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%game_cost}}".
 *
 * @property integer $game_cost_id
 * @property integer $customer_id
 * @property string $type
 * @property string $date_end
 * @property string $date_added
 * @property integer $order_id
 * @property integer $points
 * @property string $amount
 * @property string $gift_from
 * @property integer $gift_id
 * @property string $date_start
 * @property string $date_modified
 */
class GameCost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%game_cost}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'type'], 'required'],
            [['customer_id', 'order_id', 'points', 'gift_id'], 'integer'],
            [['date_end', 'date_added', 'date_start', 'date_modified'], 'safe'],
            [['amount'], 'number'],
            [['type', 'gift_from'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'game_cost_id' => 'Game Cost ID',
            'customer_id' => 'Customer ID',
            'type' => 'Type',
            'date_end' => 'Date End',
            'date_added' => 'Date Added',
            'order_id' => 'Order ID',
            'points' => 'Points',
            'amount' => 'Amount',
            'gift_from' => 'Gift From',
            'gift_id' => 'Gift ID',
            'date_start' => 'Date Start',
            'date_modified' => 'Date Modified',
        ];
    }
}
