<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%treasure_clue}}".
 *
 * @property integer $treasure_clue_id
 * @property integer $treasure_id
 * @property integer $customer_group_id
 * @property string $context
 * @property string $route
 * @property integer $sort
 * @property string $date_added
 */
class TreasureClue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%treasure_clue}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['treasure_id', 'customer_group_id', 'context', 'date_added'], 'required'],
            [['treasure_id', 'customer_group_id', 'sort'], 'integer'],
            [['context'], 'string'],
            [['date_added'], 'safe'],
            [['route'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'treasure_clue_id' => 'Treasure Clue ID',
            'treasure_id' => 'Treasure ID',
            'customer_group_id' => 'Customer Group ID',
            'context' => 'Context',
            'route' => 'Route',
            'sort' => 'Sort',
            'date_added' => 'Date Added',
        ];
    }
}
