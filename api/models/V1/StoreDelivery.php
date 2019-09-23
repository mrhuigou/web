<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_delivery}}".
 *
 * @property string $store_delivery_id
 * @property string $store_delivery_code
 * @property string $name
 * @property string $method
 * @property string $method_value
 * @property integer $store_id
 * @property string $store_code
 * @property integer $is_default
 * @property integer $status
 */
class StoreDelivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_delivery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_delivery_code', 'name', 'method', 'store_id', 'store_code'], 'required'],
            [['store_id', 'is_default', 'status'], 'integer'],
            [['store_delivery_code', 'store_code'], 'string', 'max' => 32],
            [['name', 'method_value'], 'string', 'max' => 255],
            [['method'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_delivery_id' => 'Store Delivery ID',
            'store_delivery_code' => 'Store Delivery Code',
            'name' => 'Name',
            'method' => 'Method',
            'method_value' => 'Method Value',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'is_default' => 'Is Default',
            'status' => 'Status',
        ];
    }
}
