<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_black}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $score
 * @property string $shipping_telephone
 * @property string $shipping_username
 * @property string $shipping_address
 * @property string $ip
 * @property integer $status
 * @property integer $creat_at
 */
class OrderBlack extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_black}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id','score', 'status', 'creat_at'], 'integer'],
            [['shipping_address'], 'string'],
            [['shipping_telephone', 'shipping_username', 'ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id'=>'order_id',
            'score' => 'Score',
            'shipping_telephone' => 'Shipping Telephone',
            'shipping_username' => 'Shipping Username',
            'shipping_address' => 'Shipping Address',
            'ip' => 'Ip',
            'status' => 'Status',
            'creat_at' => 'Creat At',
        ];
    }
}
