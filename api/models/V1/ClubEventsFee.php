<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_events_fee}}".
 *
 * @property string $fee_id
 * @property string $fee_detail
 * @property string $price
 * @property integer $events_id
 */
class ClubEventsFee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_events_fee}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['events_id'], 'required'],
            [['events_id'], 'integer'],
            [['fee_detail'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fee_id' => 'Fee ID',
            'fee_detail' => 'Fee Detail',
            'price' => 'Price',
            'events_id' => 'Events ID',
        ];
    }
}
