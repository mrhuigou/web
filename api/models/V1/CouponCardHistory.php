<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon_card_history}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $coupon_card_id
 * @property string $source_from
 * @property string $date_added
 */
class CouponCardHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_card_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'coupon_card_id'], 'integer'],
            [['source_from'], 'string'],
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
            'coupon_card_id' => 'Coupon Card ID',
            'source_from' => 'Source From',
            'date_added' => 'Date Added',
        ];
    }
}
