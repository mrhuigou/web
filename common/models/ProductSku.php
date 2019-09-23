<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%product_sku}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $code
 * @property string $spec_array
 * @property integer $qty
 * @property string $market_price
 * @property string $sell_price
 * @property string $cost_price
 * @property string $weight
 *
 * @property Product $product
 */
class ProductSku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_sku}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'qty'], 'integer'],
            [['spec_array'], 'string'],
            [['market_price', 'sell_price', 'cost_price', 'weight'], 'number'],
            [['code'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'SKUID',
            'product_id' => '产品表_产品ID',
            'code' => 'SKU编码',
            'spec_array' => '序列化（规格）',
            'qty' => '数量',
            'market_price' => '市场价格',
            'sell_price' => '会员价',
            'cost_price' => '成本价格',
            'weight' => '重量',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
