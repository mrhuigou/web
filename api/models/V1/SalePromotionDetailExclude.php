<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%sale_promotion_detail_exclude}}".
 *
 * @property integer $detail_exclude_id
 * @property integer $promotion_detail_id
 * @property integer $store_id
 * @property integer $product_base_id
 * @property integer $product_id
 * @property integer $status
 */
class SalePromotionDetailExclude extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sale_promotion_detail_exclude}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promotion_detail_id', 'store_id', 'product_base_id', 'product_id', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'detail_exclude_id' => 'Detail Exclude ID',
            'promotion_detail_id' => 'Promotion Detail ID',
            'store_id' => 'Store ID',
            'product_base_id' => 'Product Base ID',
            'product_id' => 'Product ID',
            'status' => 'Status',
        ];
    }
}
