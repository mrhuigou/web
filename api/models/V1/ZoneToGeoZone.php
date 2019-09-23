<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%zone_to_geo_zone}}".
 *
 * @property integer $zone_to_geo_zone_id
 * @property integer $country_id
 * @property integer $zone_id
 * @property integer $geo_zone_id
 * @property string $date_added
 * @property string $date_modified
 */
class ZoneToGeoZone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zone_to_geo_zone}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'geo_zone_id'], 'required'],
            [['country_id', 'zone_id', 'geo_zone_id'], 'integer'],
            [['date_added', 'date_modified'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'zone_to_geo_zone_id' => 'Zone To Geo Zone ID',
            'country_id' => 'Country ID',
            'zone_id' => 'Zone ID',
            'geo_zone_id' => 'Geo Zone ID',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
