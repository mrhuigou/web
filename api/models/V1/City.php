<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%city}}".
 *
 * @property integer $city_id
 * @property string $code
 * @property string $zone_code
 * @property integer $zone_id
 * @property string $name
 * @property integer $status
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code','zone_id', 'name'], 'required'],
            [['zone_id', 'status'], 'integer'],
            [['code', 'zone_code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 128],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => 'City ID',
            'code' => 'Code',
            'zone_code' => 'Zone Code',
            'zone_id' => 'Zone ID',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }
}
