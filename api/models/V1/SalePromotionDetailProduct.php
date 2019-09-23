<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%sale_promotion_detail_product}}".
 *
 * @property integer $id
 * @property integer $product_base_id
 * @property integer $product_id
 * @property integer $promotion_detail_id
 */
class SalePromotionDetailProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sale_promotion_detail_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_base_id', 'product_id', 'promotion_detail_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_base_id' => 'Product Base ID',
            'product_id' => 'Product ID',
            'promotion_detail_id' => 'Promotion Detail ID',
        ];
    }
}
