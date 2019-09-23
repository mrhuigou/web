<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_to_relate}}".
 *
 * @property integer $category_relate_to_id
 * @property integer $category_id
 * @property integer $product_id
 * @property string $code
 * @property string $pucode
 * @property integer $sort_order
 */
class CategoryToRelate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_to_relate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'product_id', 'sort_order'], 'integer'],
            [['code', 'pucode'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_relate_to_id' => 'Category Relate To ID',
            'category_id' => 'Category ID',
            'product_id' => 'Product ID',
            'code' => 'Code',
            'pucode' => 'Pucode',
            'sort_order' => 'Sort Order',
        ];
    }
}
