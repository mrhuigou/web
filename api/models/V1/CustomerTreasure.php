<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_treasure}}".
 *
 * @property string $customer_treasure_id
 * @property integer $customer_id
 * @property integer $treasure_id
 * @property integer $status
 * @property string $order_id
 * @property string $date_added
 */
class CustomerTreasure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_treasure}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'treasure_id', 'status'], 'integer'],
            [['date_added'], 'safe'],
            [['order_id'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_treasure_id' => 'Customer Treasure ID',
            'customer_id' => 'Customer ID',
            'treasure_id' => 'Treasure ID',
            'status' => '1表示购买成功可以玩游戏；0表示该游戏失败；3表示已经成功通关',
            'order_id' => '用订单id才能玩游戏',
            'date_added' => 'Date Added',
        ];
    }
}
