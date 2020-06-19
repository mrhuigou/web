<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%affiliate_transaction_statement}}".
 *
 * @property integer $id
 * @property integer $affiliate_id
 * @property string $title
 * @property string $amount
 * @property string $balance
 * @property string $reverse
 * @property string $remark
 * @property integer $create_at
 * @property integer $status
 * @property integer $update_at
 * @property string $type
 * @property integer $type_id
 */
class AffiliateTransactionStatement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_transaction_statement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['affiliate_id', 'create_at', 'status', 'update_at', 'type_id'], 'integer'],
            [['amount', 'balance', 'reverse','total'], 'number'],
            [['remark'], 'string'],
            [['title', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'affiliate_id' => 'Affiliate ID',
            'title' => 'Title',
            'amount' => 'Amount',
            'balance' => 'Balance',
            'total' => 'Total',
            'commission' => 'Commission',
            'reverse' => 'Reverse',
            'remark' => 'Remark',
            'create_at' => 'Create At',
            'status' => 'Status',
            'update_at' => 'Update At',
            'type' => 'Type',
            'type_id' => 'Type ID',
        ];
    }
}
