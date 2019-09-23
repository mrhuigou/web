<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_status_detail}}".
 *
 * @property integer $order_status_detail_id
 * @property integer $order_status_id
 * @property string $name
 * @property string $code
 * @property integer $sort_order
 */
class OrderStatusDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_status_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_status_id', 'sort_order'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_status_detail_id' => 'Order Status Detail ID',
            'order_status_id' => 'Order Status ID',
            'name' => 'Name',
            'code' => 'Code',
            'sort_order' => 'Sort Order',
        ];
    }
}
