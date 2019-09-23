<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "jr_promotion_ordertotal".
 *
 * @property integer $id
 * @property string $belong
 * @property string $type
 * @property string $total
 * @property string $product_code
 * @property string $date_start
 * @property string $date_end
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 */
class PromotionOrdertotal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jr_promotion_ordertotal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['total'], 'number'],
            [['date_start', 'date_end', 'date_added', 'date_modified'], 'safe'],
            [['status'], 'integer'],
            [['belong', 'product_code'], 'string', 'max' => 32],
            [['type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'belong' => 'Belong',
            'type' => 'Type',
            'total' => 'Total',
            'product_code' => 'Product Code',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'status' => 'Status',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_code'=>'product_code']);
    }
}
