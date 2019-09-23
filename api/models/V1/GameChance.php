<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%game_chance}}".
 *
 * @property integer $game_chance_id
 * @property integer $customer_id
 * @property string $expiration_time
 * @property string $from
 * @property integer $from_id
 * @property integer $status
 * @property string $date_added
 */
class GameChance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%game_chance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'from_id', 'status'], 'integer'],
            [['expiration_time', 'date_added'], 'safe'],
            [['from'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'game_chance_id' => 'Game Chance ID',
            'customer_id' => 'Customer ID',
            'expiration_time' => 'Expiration Time',
            'from' => 'From',
            'from_id' => 'From ID',
            'status' => 'Status',
            'date_added' => 'Date Added',
        ];
    }
}
