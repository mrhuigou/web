<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_treasure_clue}}".
 *
 * @property integer $customer_treasure_cule_id
 * @property integer $customer_id
 * @property integer $treasure_id
 * @property integer $treasure_clue_id
 * @property integer $status
 * @property string $date_added
 * @property string $date_complete
 * @property integer $customer_treasure_id
 */
class CustomerTreasureClue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_treasure_clue}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'treasure_id', 'treasure_clue_id', 'status', 'customer_treasure_id'], 'integer'],
            [['date_added', 'date_complete'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_treasure_cule_id' => 'Customer Treasure Cule ID',
            'customer_id' => 'Customer ID',
            'treasure_id' => 'Treasure ID',
            'treasure_clue_id' => 'Treasure Clue ID',
            'status' => '0表示已经注册该线索，1表示已经完成该线索；',
            'date_added' => '线索添加时间',
            'date_complete' => '该线索完成时间',
            'customer_treasure_id' => 'Customer Treasure ID',
        ];
    }
}
