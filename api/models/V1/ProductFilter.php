<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_filter}}".
 *
 * @property integer $product_id
 * @property integer $filter_id
 */
class ProductFilter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_filter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'filter_id'], 'required'],
            [['product_id', 'filter_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'filter_id' => 'Filter ID',
        ];
    }
}
