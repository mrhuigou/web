<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_to_key}}".
 *
 * @property integer $customer_id
 * @property string $keycode
 */
class CustomerToKey extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_to_key}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            [['customer_id'], 'integer'],
            [['keycode'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'keycode' => 'Keycode',
        ];
    }
}
