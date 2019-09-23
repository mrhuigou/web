<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%cycle_detail}}".
 *
 * @property string $cycle_detail_id
 * @property integer $cycle_id
 * @property integer $product_id
 * @property string $product_code
 * @property integer $quantity
 * @property string $created_at
 * @property string $updated_at
 */
class CycleDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cycle_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cycle_id', 'product_id', 'quantity'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['product_code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cycle_detail_id' => 'Cycle Detail ID',
            'cycle_id' => 'Cycle ID',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'quantity' => 'Quantity',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
