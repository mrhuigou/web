<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_base_to_layout}}".
 *
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property string $platform_code
 * @property integer $layout_id
 */
class ProductBaseToLayout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_base_to_layout}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_base_id', 'product_base_code', 'platform_code', 'layout_id'], 'required'],
            [['product_base_id', 'layout_id'], 'integer'],
            [['product_base_code', 'platform_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
            'platform_code' => 'Platform Code',
            'layout_id' => 'Layout ID',
        ];
    }
}
