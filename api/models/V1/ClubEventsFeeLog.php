<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_events_fee_log}}".
 *
 * @property integer $fl_id
 * @property integer $fee_id
 * @property integer $customer_id
 * @property string $price
 * @property integer $events_id
 */
class ClubEventsFeeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_events_fee_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fee_id', 'customer_id', 'events_id'], 'required'],
            [['fee_id', 'customer_id', 'events_id'], 'integer'],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fl_id' => 'Fl ID',
            'fee_id' => 'Fee ID',
            'customer_id' => 'Customer ID',
            'price' => 'Price',
            'events_id' => 'Events ID',
        ];
    }
}
