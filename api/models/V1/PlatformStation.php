<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%station}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property string $contact_name
 * @property string $telephone
 * @property string $open_time
 * @property string $description
 * @property string $latitude
 * @property string $longitude
 * @property integer $zone_id
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $store_id
 * @property integer $platform_id
 * @property integer $is_fresh
 * @property integer $is_open
 * @property integer $status
 */
class PlatformStation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%platform_station}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address', 'description'], 'string'],
            [['store_id', 'platform_id','zone_id','city_id','district_id', 'is_fresh', 'is_open', 'status'], 'integer'],
            [['platform_id'], 'required'],
            [['code', 'name', 'contact_name', 'telephone', 'open_time', 'latitude', 'longitude'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'address' => 'Address',
            'contact_name' => 'Contact Name',
            'telephone' => 'Telephone',
            'open_time' => 'Open Time',
            'description' => 'Description',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'zone_id' => 'Zone ID',
            'city_id' => 'City ID',
            'district_id' => 'District ID',
            'store_id' => 'Store ID',
            'platform_id' => 'Platform ID',
            'is_fresh' => 'Is Fresh',
            'is_open' => 'Is Open',
            'status' => 'Status',
        ];
    }
    public function getZone(){
        return $this->hasOne(Zone::className(),['zone_id'=>'zone_id']);
    }
    public function getCity(){
        return $this->hasOne(City::className(),['city_id'=>'city_id']);
    }
    public function getDistrict(){
        return $this->hasOne(District::className(),['district_id'=>'district_id']);
    }
}
