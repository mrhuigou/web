<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%sale_promotion_detail_gift}}".
 *
 * @property integer $id
 * @property string $code
 * @property integer $store_id
 * @property integer $product_base_id
 * @property integer $product_id
 * @property integer $qty
 * @property integer $be_have_limit
 * @property integer $be_have_money
 * @property integer $be_need_money
 * @property integer $status
 * @property integer $promotion_detail_id
 */
class SalePromotionDetailGift extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sale_promotion_detail_gift}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'product_base_id', 'product_id', 'qty', 'be_have_limit', 'be_have_money', 'be_need_money', 'status', 'promotion_detail_id'], 'integer'],
            [['code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'store_id' => 'Store ID',
            'product_base_id' => 'Product Base ID',
            'product_id' => 'Product ID',
            'qty' => 'Qty',
            'be_have_limit' => 'Be Have Limit',
            'be_have_money' => 'Be Have Money',
            'be_need_money' => 'Be Need Money',
            'status' => 'Status',
            'promotion_detail_id' => 'Promotion Detail ID',
        ];
    }
}
