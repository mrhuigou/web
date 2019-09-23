<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_special}}".
 *
 * @property integer $product_special_id
 * @property integer $product_id
 * @property string $product_code
 * @property integer $customer_group_id
 * @property integer $priority
 * @property string $price
 * @property integer $needHour
needHour
needhour
 * @property string $date_start
 * @property string $date_end
 */
class ProductSpecial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_special}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'product_code', 'customer_group_id'], 'required'],
            [['product_id', 'customer_group_id', 'priority', 'needHour
needHour
needhour'], 'integer'],
            [['price'], 'number'],
            [['date_start', 'date_end'], 'safe'],
            [['product_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_special_id' => 'Product Special ID',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'customer_group_id' => 'Customer Group ID',
            'priority' => 'Priority',
            'price' => 'Price',
            'needHour
needHour
needhour' => '是否有小时约束',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
        ];
    }
}
