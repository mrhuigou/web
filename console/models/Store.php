<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%store}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $url
 * @property string $logo
 * @property string $zone
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $telephone
 * @property string $longitude
 * @property string $latitude
 * @property integer $status
 * @property string $creat_datetime
 * @property string $update_datetime
 *
 * @property StoreTemplate[] $storeTemplates
 * @property StoreTemplatePage[] $storeTemplatePages
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
            [['longitude', 'latitude'], 'number'],
            [['status'], 'integer'],
            [['creat_datetime', 'update_datetime'], 'safe'],
            [['code', 'zone', 'city', 'district', 'telephone'], 'string', 'max' => 50],
            [['name', 'url', 'logo', 'address'], 'string', 'max' => 255]
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
            'url' => '店铺网址',
            'logo' => '店铺LOGO',
            'zone' => '店铺所属省份',
            'city' => '店铺所属城市',
            'district' => '店铺所属区市',
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
    public function getStoreTemplates()
    {
        return $this->hasMany(StoreTemplate::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreTemplatePages()
    {
        return $this->hasMany(StoreTemplatePage::className(), ['store_id' => 'id']);
    }
}
