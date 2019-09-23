<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_authen}}".
 *
 * @property integer $customer_authen_id
 * @property integer $customer_id
 * @property string $description
 * @property string $date_added
 */
class CustomerAuthen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_authen}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'integer'],
            [['description'], 'string'],
            [['date_added'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_authen_id' => '该表记录身份认证未通过的原因',
            'customer_id' => 'Customer ID',
            'description' => 'Description',
            'date_added' => 'Date Added',
        ];
    }
}
