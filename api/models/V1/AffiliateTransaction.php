<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%affiliate_transaction}}".
 *
 * @property integer $affiliate_transaction_id
 * @property integer $affiliate_id
 * @property string $amount
 */
class AffiliateTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['affiliate_id', 'amount',], 'required'],
            [['affiliate_id'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'affiliate_transaction_id' => '编号',
            'affiliate_id' => '用户ID',
            'amount' => '金额'
        ];
    }

    public function getAffiliate()
    {
        return $this->hasOne(Affiliate::className(), ['affiliate_id' => 'affiliate_id']);
    }
}
