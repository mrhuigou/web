<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%promotion_product_group_detail}}".
 *
 * @property integer $promotion_product_group_detail_id
 * @property integer $promotion_product_group_id
 * @property integer $product_id
 * @property integer $product_code
 * @property integer $base_quantity
 * @property string $price
 * @property integer $store_id
 * @property string $store_code
 * @property string $uplimit_type
 * @property integer $uplimit_quantity
 * @property integer $status
 */
class PromotionProductGroupDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%promotion_product_group_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promotion_product_group_id', 'product_id'], 'required'],
            [['promotion_product_group_id', 'product_id', 'product_code', 'base_quantity', 'store_id', 'uplimit_quantity', 'status'], 'integer'],
            [['price'], 'number'],
            [['store_code'], 'string', 'max' => 40],
            [['uplimit_type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'promotion_product_group_detail_id' => 'Promotion Product Group Detail ID',
            'promotion_product_group_id' => '商品组合ID',
            'product_id' => '商品ID',
            'product_code' => 'Product Code',
            'base_quantity' => '基数',
            'price' => 'Price',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'uplimit_type' => '上限类型：指定数量、实际库存',
            'uplimit_quantity' => '上限数量(库存数量)',
            'status' => '有效状态，0=无效，1=生效',
        ];
    }
}
