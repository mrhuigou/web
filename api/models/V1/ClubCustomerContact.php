<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_customer_contact}}".
 *
 * @property integer $cr_id
 * @property integer $customer_id
 * @property string $phone_number
 */
class ClubCustomerContact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_customer_contact}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'phone_number'], 'required'],
            [['customer_id'], 'integer'],
            [['phone_number'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cr_id' => 'Cr ID',
            'customer_id' => 'Customer ID',
            'phone_number' => 'Phone Number',
        ];
    }
}
