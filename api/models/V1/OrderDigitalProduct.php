<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_digital_product}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $code
 * @property string $type
 * @property string $name
 * @property string $account
 * @property string $model
 * @property integer $qty
 * @property string $price
 * @property string $total
 * @property string $callback_id
 * @property string $callback_data
 * @property integer $status
 */
class OrderDigitalProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_digital_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'qty', 'status'], 'integer'],
            [['price', 'total'], 'number'],
            [['callback_data'], 'string'],
            [['code', 'type'], 'string', 'max' => 50],
            [['name', 'account', 'model', 'callback_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'code' => '充值的商品code',
            'type' => '充值类型（account,账户充值，telephone,手机充值）',
            'name' => '名称',
            'account' => '充值账户',
            'model' => '类型',
            'qty' => '数量',
            'price' => '价格',
            'total' => '总计',
            'callback_id' => 'Callback ID',
            'callback_data' => 'Callback Data',
            'status' => '状态',
        ];
    }
}
