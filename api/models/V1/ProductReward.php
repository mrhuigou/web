<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_reward}}".
 *
 * @property integer $product_reward_id
 * @property string $product_code
 * @property integer $customer_group_id
 * @property integer $points
 * @property integer $product_id
 */
class ProductReward extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_reward}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_group_id', 'points', 'product_id'], 'integer'],
            [['product_id'], 'required'],
            [['product_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_reward_id' => 'Product Reward ID',
            'product_code' => 'Product Code',
            'customer_group_id' => 'Customer Group ID',
            'points' => 'Points',
            'product_id' => 'Product ID',
        ];
    }
}
