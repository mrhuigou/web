<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%seller_to_message}}".
 *
 * @property integer $seller_id
 * @property integer $message_id
 */
class SellerToMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seller_to_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seller_id', 'message_id'], 'required'],
            [['seller_id', 'message_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seller_id' => 'Seller ID',
            'message_id' => 'Message ID',
        ];
    }
}
