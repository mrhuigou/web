<?php

namespace api\models\V1;
use Yii;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property integer $address_id
 * @property integer $customer_id
 * @property string $firstname
 * @property string $telephone
 * @property string $address_1
 * @property string $postcode
 * @property integer $country_id
 * @property integer $zone_id
 * @property integer $city_id
 * @property integer $district_id
 * @property string $poiname
 * @property string $poiaddress
 * @property string $lat
 * @property string $lng
 * @property string $date_added
 * @property string $date_modified
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $is_default=true;
    public static function tableName()
    {
        return '{{%address}}';
    }

    /**
     * @inheritdoc
     */

    /**
     * @inheritdoc
     */
    public function attributeLabels(){
        return ['firstname'=>'收货人',
            'telephone'=>'手机号码',
            'district_id'=>'所在区域',
            'address_1'=>'街道地址',
            'postcode'=>'邮政编码',
             'is_default'=>'设置为默认'
        ];
    }
    public function getDistrict(){
        return $this->hasOne(District::className(),['district_id'=>'district_id']);
    }
    public function getCitys(){
        return $this->hasOne(City::className(),['city_id'=>'city_id']);
    }
    public function getZone(){
        return $this->hasOne(Zone::className(),['zone_id'=>'zone_id']);
    }
    public function getDistrictInUse(){
        return $this->hasOne(District::className(),['district_id'=>'district_id'])->where(['is_use'=>1]);
    }
    public function getCitysInUse(){
        return $this->hasOne(City::className(),['city_id'=>'city_id'])->where(['is_use'=>1]);
    }
    public function getZoneInUse(){
        return $this->hasOne(Zone::className(),['zone_id'=>'zone_id'])->where(['is_use'=>1]);
    }
    public function getCountry(){
        return $this->hasOne(Country::className(),['country_id'=>'country_id']);
    }
    public function getDefault(){
        if(!Yii::$app->user->isGuest){
            if(!$address_id=Yii::$app->session->get('checkout_address_id')){
                $address_id=Yii::$app->user->identity->address_id;
            }
            return $this->address_id==$address_id;
        }else{
            return false;
        }

    }
    public function GetIfInRange(){
        if($this->district_id){
            $active_districts = District::find()->select('district_id')->where(['is_use'=>1])->all();
            foreach ($active_districts as $active_district){
                $district_array[] = $active_district->district_id;
            }
            if(in_array($this->district_id,$district_array)){
                return true;
            }
        }
        return false;

    }
}
