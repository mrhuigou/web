<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_status}}".
 *
 * @property integer $order_status_id
 * @property integer $language_id
 * @property string $name
 * @property string $code
 * @property integer $sort_order
 * @property integer $type_id
 */
class OrderStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'name'], 'required'],
            [['language_id', 'sort_order', 'type_id'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['code'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_status_id' => 'Order Status ID',
            'language_id' => 'Language ID',
            'name' => '订单状态',
            'code' => 'Code',
            'sort_order' => 'Sort Order',
            'type_id' => '所属类型 默认值1，1=订单交易状态，2=物流状态',
        ];
    }
}
