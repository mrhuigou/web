<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_try_coupon}}".
 *
 * @property integer $id
 * @property integer $try_id
 * @property integer $coupon_id
 */
class ClubTryCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_try_coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['try_id', 'coupon_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'try_id' => 'Try ID',
            'coupon_id' => 'Coupon ID',
        ];
    }
}
