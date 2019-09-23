<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_map}}".
 *
 * @property integer $customer_map_id
 * @property integer $customer_id
 * @property double $latitude
 * @property double $longitude
 * @property double $precision
 * @property integer $data_added
 */
class CustomerMap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_map}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'data_added'], 'integer'],
            [['latitude', 'longitude', 'precision'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_map_id' => 'Customer Map ID',
            'customer_id' => 'Customer ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'precision' => 'Precision',
            'data_added' => 'Data Added',
        ];
    }
}
