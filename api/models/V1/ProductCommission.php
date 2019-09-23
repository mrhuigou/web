<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_commission}}".
 *
 * @property integer $id
 * @property string $code
 * @property integer $product_id
 * @property string $commission_type
 * @property string $commission
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 */
class ProductCommission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_commission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'status'], 'integer'],
            [['commission'], 'number'],
            [['start_time', 'end_time'], 'safe'],
            [['code', 'commission_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'product_id' => 'Product ID',
            'commission_type' => 'Commission Type',
            'commission' => 'Commission',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'status' => 'Status',
        ];
    }
}
