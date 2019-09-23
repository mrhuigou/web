<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%warehouse_stock_schedule}}".
 *
 * @property integer $warehouse_stock_schedule_id
 * @property integer $warehouse_id
 * @property string $product_code
 * @property string $schedule_date
 * @property integer $quantity
 */
class WarehouseStockSchedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%warehouse_stock_schedule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'product_code'], 'required'],
            [['warehouse_id', 'quantity'], 'integer'],
            [['schedule_date'], 'safe'],
            [['product_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'warehouse_stock_schedule_id' => 'Warehouse Stock Schedule ID',
            'warehouse_id' => 'Warehouse ID',
            'product_code' => 'Product Code',
            'schedule_date' => 'Schedule Date',
            'quantity' => 'Quantity',
        ];
    }
}
