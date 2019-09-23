<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%invoice}}".
 *
 * @property integer $invoice_id
 * @property integer $type_invoice
 * @property string $title_invoice
 * @property string $code
 * @property string $address_and_phone
 * @property string $bank_and_account
 * @property string $image_tax
 * @property string $image_tax_man
 * @property integer $customer_id
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_invoice', 'customer_id'], 'integer'],
            [['title_invoice', 'code', 'address_and_phone', 'bank_and_account', 'image_tax', 'image_tax_man'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_id' => 'Invoice ID',
            'type_invoice' => '发票类型',
            'title_invoice' => '发票票头',
            'code' => '纳税人识别号',
            'address_and_phone' => '公司注册地址及联系电话',
            'bank_and_account' => '公司开户行及账号',
            'image_tax' => 'Image Tax',
            'image_tax_man' => 'Image Tax Man',
            'customer_id' => 'Customer ID',
        ];
    }
}
