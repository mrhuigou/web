<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%ground_push_stock_log}}".
 *
 * @property integer $id
 * @property integer $ground_push_point_id
 * @property string $type
 * @property string $product_code
 * @property integer $qty
 * @property string $last_time
 */
class GroundPushStockLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ground_push_stock_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ground_push_point_id', 'qty'], 'integer'],
            [['last_time'], 'safe'],
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
            'ground_push_point_id' => 'Ground Push Point ID',
            'type' => 'Type',
            'product_code' => 'Product Code',
            'qty' => 'Qty',
            'last_time' => 'Last Time',
        ];
    }
}
