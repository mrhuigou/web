<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%store}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $logo
 * @property integer $market_id
 * @property integer $legal_person_id
 * @property integer $business_zone_id
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $district_id
 * @property string $address
 * @property string $telephone
 * @property string $longitude
 * @property string $latitude
 * @property integer $status
 * @property string $creat_datetime
 * @property string $update_datetime
 *
 * @property BusinessZone $businessZone
 * @property Province $province
 * @property City $city
 * @property District $district
 * @property Market $market
 * @property LegalPerson $legalPerson
 * @property StoreTemplate[] $storeTemplates
 * @property StoreTemplatePages[] $storeTemplatePages
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['market_id', 'legal_person_id', 'business_zone_id', 'province_id', 'city_id', 'district_id', 'status'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['creat_datetime', 'update_datetime'], 'safe'],
            [['code', 'telephone'], 'string', 'max' => 50],
            [['name', 'title', 'url', 'logo', 'address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '店铺ID',
            'code' => '店铺code',
            'name' => '店铺名称',
            'title' => 'Title',
            'description' => 'Description',
            'url' => '店铺网址',
            'logo' => '店铺LOGO',
            'market_id' => 'Market ID',
            'legal_person_id' => 'Legal Person ID',
            'business_zone_id' => 'Business Zone ID',
            'province_id' => '店铺所属省份',
            'city_id' => '店铺所属城市',
            'district_id' => '店铺所属区市',
            'address' => '店铺详细地址',
            'telephone' => '店铺电话',
            'longitude' => '店铺经度',
            'latitude' => '店铺纬度',
            'status' => '店铺状态',
            'creat_datetime' => '店铺注册时间',
            'update_datetime' => '店铺开通时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessZone()
    {
        return $this->hasOne(BusinessZone::className(), ['id' => 'business_zone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarket()
    {
        return $this->hasOne(Market::className(), ['id' => 'market_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegalPerson()
    {
        return $this->hasOne(LegalPerson::className(), ['id' => 'legal_person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreTemplates()
    {
        return $this->hasMany(StoreTemplate::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreTemplatePages()
    {
        return $this->hasMany(StoreTemplatePages::className(), ['store_id' => 'id']);
    }
}
