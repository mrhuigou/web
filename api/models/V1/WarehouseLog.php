<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%warehouse_log}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $product_code
 * @property integer $qty
 * @property integer $create_time
 */
class WarehouseLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%warehouse_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qty', 'create_time'], 'integer'],
            [['type', 'product_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'product_code' => 'Product Code',
            'qty' => 'Qty',
            'create_time' => 'Create Time',
        ];
    }
}
