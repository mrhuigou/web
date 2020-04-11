<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_commission_draw}}".
 *
 * @property integer $id
 * @property string $code
 * @property integer $affiliate_id
 * @property string $amount
 * @property integer $status
 * @property integer $created_at
 * @property integer $update_at
 * @property string $trade_no
 * @property string $remark
 * @property string $open_id
 */
class AffiliateTransactionDraw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_transaction_draw}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['affiliate_id', 'status', 'created_at', 'update_at'], 'integer'],
            [['amount'], 'number'],
            [['remark'], 'string'],
            [['code', 'trade_no','open_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'affiliate_id' => 'Affiliate_id ID',
            'amount' => 'Amount',
            'status' => 'Status',
            'created_at' => 'Created At',
            'update_at' => 'Update At',
            'trade_no' => 'Trade No',
            'remark' => 'Remark',
        ];
    }

}
