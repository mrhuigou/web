<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%manufacturer_to_store}}".
 *
 * @property integer $manufacturer_id
 * @property integer $store_id
 */
class ManufacturerToStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%manufacturer_to_store}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manufacturer_id', 'store_id'], 'required'],
            [['manufacturer_id', 'store_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manufacturer_id' => 'Manufacturer ID',
            'store_id' => 'Store ID',
        ];
    }
}
