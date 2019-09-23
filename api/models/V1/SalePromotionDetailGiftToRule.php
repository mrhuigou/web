<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%sale_promotion_detail_gift_to_rule}}".
 *
 * @property integer $promotion_detail_gift_id
 * @property integer $rule_id
 * @property integer $status
 */
class SalePromotionDetailGiftToRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sale_promotion_detail_gift_to_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promotion_detail_gift_id', 'rule_id'], 'required'],
            [['promotion_detail_gift_id', 'rule_id', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'promotion_detail_gift_id' => 'Promotion Detail Gift ID',
            'rule_id' => 'Rule ID',
            'status' => 'Status',
        ];
    }
}
