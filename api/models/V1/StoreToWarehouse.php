<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_to_warehouse}}".
 *
 * @property string $store_to_warehouse_id
 * @property integer $store_id
 * @property string $store_code
 * @property integer $warehouse_id
 * @property string $warehouse_code
 */
class StoreToWarehouse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_to_warehouse}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'warehouse_id'], 'integer'],
            [['store_code', 'warehouse_code'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_to_warehouse_id' => 'Store To Warehouse ID',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'warehouse_id' => 'Warehouse ID',
            'warehouse_code' => 'Warehouse Code',
        ];
    }
}
