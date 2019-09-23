<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_delivery_detail}}".
 *
 * @property integer $store_delivery_id
 * @property string $store_delivery_code
 * @property integer $district_id
 * @property string $district_code
 * @property string $district_name
 */
class StoreDeliveryDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_delivery_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_delivery_id', 'store_delivery_code', 'district_id', 'district_code', 'district_name'], 'required'],
            [['store_delivery_id', 'district_id'], 'integer'],
            [['store_delivery_code', 'district_code'], 'string', 'max' => 32],
            [['district_name'], 'string', 'max' => 255]
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
            'district_id' => 'District ID',
            'district_code' => 'District Code',
            'district_name' => 'District Name',
        ];
    }
}
