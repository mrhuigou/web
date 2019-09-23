<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%warehouse_stock}}".
 *
 * @property string $warehouse_stock_id
 * @property integer $warehouse_id
 * @property string $product_code
 * @property integer $quantity
 * @property integer $tmp_qty
 * @property integer $cycle_period
 * @property string $product_date
 */
class WarehouseStock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%warehouse_stock}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'quantity', 'cycle_period','tmp_qty'], 'integer'],
            [['product_code','product_date'], 'string', 'max' => 50],
            [['version'], 'safe'],
        ];
    }
    public function optimisticLock(){
        return 'version';

    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'warehouse_stock_id' => 'Warehouse Stock ID',
            'warehouse_id' => 'Warehouse ID',
            'product_code' => 'Product Code',
            'quantity' => 'Quantity',
            'cycle_period' => 'Cycle Period',
            'tmp_qty'=>'tmp_qty'
        ];
    }
    public function getProduct(){
    	return $this->hasOne(Product::className(),['product_code'=>'product_code']);
    }
}
