<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%express_card_product}}".
 *
 * @property integer $id
 * @property integer $express_card_id
 * @property string $shop_code
 * @property string $product_base_code
 * @property string $product_code
 * @property string $product_name
 * @property integer $quantity
 * @property string $description
 * @property integer $status
 */
class ExpressCardProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%express_card_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['express_card_id'], 'required'],
            [['express_card_id', 'quantity', 'status'], 'integer'],
            [['description'], 'string'],
            [['shop_code', 'product_base_code', 'product_code', 'product_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'express_card_id' => 'Express Card ID',
            'shop_code' => 'Shop Code',
            'product_base_code' => 'Product Base Code',
            'product_code' => 'Product Code',
            'product_name' => 'Product Name',
            'quantity' => 'Quantity',
            'description' => 'Description',
            'status' => 'Status',
        ];
    }
}
