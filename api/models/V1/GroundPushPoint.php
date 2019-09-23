<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%ground_push_point}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $type
 * @property string $name
 * @property string $zone_code
 * @property string $zone_name
 * @property string $city_code
 * @property string $city_name
 * @property string $district_code
 * @property string $district_name
 * @property string $address
 * @property string $contact_name
 * @property string $contact_tel
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 */
class GroundPushPoint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ground_push_point}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'create_at', 'update_at'], 'integer'],
            [['code', 'name', 'zone_code', 'zone_name', 'city_code', 'city_name', 'district_code', 'district_name', 'address', 'contact_name', 'contact_tel'], 'string', 'max' => 255],
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
            'zone_code' => 'Zone Code',
            'zone_name' => 'Zone Name',
            'city_code' => 'City Code',
            'city_name' => 'City Name',
            'district_code' => 'District Code',
            'district_name' => 'District Name',
            'address' => 'Address',
            'contact_name' => 'Contact Name',
            'contact_tel' => 'Contact Tel',
            'status' => 'Status',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
    public function getZone(){
        return $this->hasOne(Zone::className(),['code'=>'zone_code']);
    }
    public function getCity(){
        return $this->hasOne(City::className(),['code'=>'city_code']);
    }
    public function getDistrict(){
        return $this->hasOne(District::className(),['code'=>'district_code']);
    }
}
