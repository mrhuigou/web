<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_product_group}}".
 *
 * @property integer $order_product_group_id
 * @property integer $order_id
 * @property integer $promotion_id
 * @property string $promotion_code
 * @property string $promotion_name
 * @property string $from_group_table
 * @property integer $from_group_id
 */
class OrderProductGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_product_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'from_group_id'], 'required'],
            [['order_id', 'promotion_id', 'from_group_id'], 'integer'],
            [['promotion_code'], 'string', 'max' => 40],
            [['promotion_name'], 'string', 'max' => 125],
            [['from_group_table'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_product_group_id' => 'Order Product Group ID',
            'order_id' => 'Order ID',
            'promotion_id' => 'Promotion ID',
            'promotion_code' => '促销编码',
            'promotion_name' => 'Promotion Name',
            'from_group_table' => 'product/promotion_detail/promotion_group',
            'from_group_id' => 'From Group ID',
        ];
    }
}
