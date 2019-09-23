<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%ground_push_stock}}".
 *
 * @property integer $id
 * @property integer $ground_push_point_id
 * @property string $product_code
 * @property integer $qty
 * @property integer $tmp_qty
 * @property string $last_time
 */
class GroundPushStock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ground_push_stock}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ground_push_point_id', 'qty', 'tmp_qty'], 'integer'],
            [['last_time','version'], 'safe'],
            [['product_code'], 'string', 'max' => 255],
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
            'id' => 'ID',
            'ground_push_point_id' => 'Ground Push Point ID',
            'product_code' => 'Product Code',
            'qty' => 'Qty',
            'tmp_qty' => 'Tmp Qty',
            'last_time' => 'Last Time',
        ];
    }
    public function getQuantity(){
        $tmp_quantity = 0;
        if($this->tmp_qty >0 ){
            $tmp_quantity = $this->tmp_qty;
        }
        $quantity = $this->qty - $tmp_quantity;
        return $quantity;
    }
    public function getPoint(){
        return $this->hasOne(GroundPushPoint::className(),['id'=>'ground_push_point_id']);
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_code'=>'product_code']);
    }
}
