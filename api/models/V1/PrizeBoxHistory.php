<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%prize_box_history}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $prize_box_id
 * @property string $date_added
 */
class PrizeBoxHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prize_box_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'customer_id', 'prize_box_id'], 'integer'],
            [['date_added'], 'safe'],
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
            'prize_box_id' => 'Prize Box ID',
            'date_added' => 'Date Added',
        ];
    }
}
