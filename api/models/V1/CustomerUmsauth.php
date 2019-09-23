<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_umsauth}}".
 *
 * @property string $customer_umsauth_id
 * @property integer $customer_id
 * @property string $idcard
 * @property string $union_card
 * @property integer $status
 */
class CustomerUmsauth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_umsauth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'status'], 'integer'],
            [['idcard'], 'string', 'max' => 20],
            [['union_card'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_umsauth_id' => 'Customer Umsauth ID',
            'customer_id' => 'Customer ID',
            'idcard' => '身份证号',
            'union_card' => '银行卡号',
            'status' => '0还未认证，1认证成功, -1认证失败',
        ];
    }
}
