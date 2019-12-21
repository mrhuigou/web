<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store}}".
 *
 * @property integer $store_id
 * @property string $store_code
 * @property string $name
 * @property string $store_type
 * @property string $url
 * @property string $ssl
 * @property string $image
 * @property string $logo
 * @property string $app_logo
 * @property integer $platform_id
 * @property string $platform_code
 * @property integer $legal_person_id
 * @property string $legal_person_code
 * @property integer $theme_id
 * @property string $theme_code
 * @property string $h5_theme_id
 * @property string $date_added
 * @property string $date_modified
 * @property integer $status
 * @property integer $recommend
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $longitude
 * @property string $latitude
 * @property integer $radius
 * @property string $perprice
 * @property integer $favourite
 * @property integer $sort_order
 * @property integer $industry_store_category_id
 * @property string $industry_store_category_code
 * @property string $minbookcash
 * @property string $deliverycash
 * @property string $im_service
 * @property integer $cycle_period
 * @property string $opening_hours
 * @property string $max_open_hour
 * @property string $min_open_hour
 * @property string $delivery_hours
 * @property string $max_delivery_hour
 * @property string $min_delivery_hour
 * @property integer $is_merge
 * @property integer $is_delivery_station
 * @property integer $online
 * @property integer $befreepostage
 * @property integer $besupportpos
 * @property integer $max_user_number
 * @property string $discount
 * @property string $hotline
 * @property string $business_code
 * @property string $business_name
 * @property string $notice
 * @property string $tags
 * @property string $description
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
            [['platform_id', 'legal_person_id', 'theme_id', 'status', 'recommend', 'radius', 'favourite','h5_theme_id', 'sort_order','city', 'district', 'industry_store_category_id', 'cycle_period', 'is_merge', 'online', 'befreepostage', 'besupportpos', 'max_user_number'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['minbookcash', 'deliverycash', 'discount'], 'number'],
            [['im_service', 'opening_hours', 'delivery_hours', 'notice', 'description'], 'string'],
            [['store_code', 'platform_code', 'legal_person_code', 'hotline', 'business_code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 64],
            [['store_type', 'theme_code',  'industry_store_category_code'], 'string', 'max' => 32],
            [['url', 'ssl', 'image', 'logo', 'app_logo', 'address', 'longitude', 'latitude', 'perprice', 'business_name', 'tags'], 'string', 'max' => 255],
            [['max_open_hour', 'min_open_hour', 'max_delivery_hour', 'min_delivery_hour'], 'string', 'max' => 10],
            [['store_code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'name' => 'Name',
            'store_type' => 'Store Type',
            'url' => 'Url',
            'ssl' => 'Ssl',
            'image' => 'Image',
            'logo' => '建议大小98*106',
            'app_logo' => '移动端使用的店铺LOGO',
            'platform_id' => 'Platform ID',
            'platform_code' => 'Platform Code',
            'legal_person_id' => 'Legal Person ID',
            'legal_person_code' => 'Legal Person Code',
            'theme_id' => '模板id',
            'h5_theme_id'=>'h5_theme_id',
            'theme_code' => 'Theme Code',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'status' => '店铺状态',
            'recommend' => 'Recommend',
            'city' => 'City',
            'district' => 'District',
            'address' => 'Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'radius' => '外卖西点等 配送半径 单位 M',
            'perprice' => 'Perprice',
            'favourite' => '喜欢该店铺的客户数',
            'sort_order' => 'Sort Order',
            'industry_store_category_id' => 'Industry Store Category ID',
            'industry_store_category_code' => 'Industry Store Category Code',
            'minbookcash' => '最小起送金额',
            'deliverycash' => '不满足最小起订费时加收的运费',
            'im_service' => '客服js码',
            'cycle_period' => '7',
            'opening_hours' => 'Opening Hours',
            'max_open_hour' => '最大营业时间',
            'min_open_hour' => '最小营业时间',
            'delivery_hours' => '配送时间段',
            'max_delivery_hour' => 'Max Delivery Hour',
            'min_delivery_hour' => 'Min Delivery Hour',
            'is_merge' => '1=可合单，0=独立订单',
            'online' => 'Online',
            'is_delivery_station'=>'是否支持自提',
            'befreepostage' => '0代表不包邮，1代表包邮',
            'besupportpos' => '0,代表不支持货到卡付，1代表支付货到卡付',
            'max_user_number' => '最大容纳人数',
            'discount' => '优惠率',
            'hotline' => '服务热线',
            'business_code' => 'Business Code',
            'business_name' => 'Business Name',
            'notice' => 'Notice',
            'tags' => 'Tags',
            'description' => 'Description',
        ];
    }
    public function getTemplate(){
        return $this->hasMany(StoreTemplate::className(),['store_id'=>'store_id'])->andOnCondition(['status'=>1]);
    }
    public function getTheme(){
        return $this->hasOne(StoreTheme::className(),['store_id'=>'store_id'])->andOnCondition(['status'=>1,'theme_id'=>$this->theme_id]);
    }
    public function getH5Theme(){
        return $this->hasOne(StoreTheme::className(),['store_id'=>'store_id'])->andOnCondition(['status'=>1,'theme_id'=>$this->h5_theme_id]);
    }
    public function getCollect(){
        return $this->hasMany(CustomerCollect::className(),['store_id'=>'store_id'])->andOnCondition(['type_id'=>2]);
    }
    public function getMyCollectStatus(){
        return $this->hasOne(CustomerCollect::className(),['store_id'=>'store_id'])->andOnCondition(['type_id'=>2,'customer_id'=>Yii::$app->user->getId()]);
    }
    public function getStoreDescription(){
        return $this->hasOne(StoreDescription::className(),['store_id'=>'store_id']);
    }
    public function getProducts(){
        return $this->hasMany(ProductBase::className(),['store_id'=>'store_id'])->orderBy('sort_order')->limit(4);
    }
    public function getTopProducts($limit=4){
        return $this->hasMany(ProductBase::className(),['store_id'=>'store_id'])->andOnCondition(['beintoinv'=>1])->orderBy('date_added desc')->limit($limit);

    }
    public function getShippingMethod(){
        if($this->is_delivery_station){
            return ['any'=>'不限','delivery'=>'每日惠购配送','self-delivery'=>'用户自提'];
        }else{
            return ['any'=>'不限','delivery'=>'每日惠购配送'];
        }
    }
    //自提点
    public function getStation(){
        if($this->store_id){
            $model=PlatformStation::find()->where(['platform_id'=>1,'status'=>1])->andWhere(['or',"store_id=".$this->store_id,"is_open=1"])->all();
            return $model;
        }else{
            return null;
        }
    }
    public function getWarehouse(){
    	return $this->hasOne(StoreToWarehouse::className(),['store_id'=>'store_id']);
    }
    public function getCategorys(){
        return $this->hasMany(CategoryStore::className(),['store_id'=>'store_id'])->where(['parent_id'=>0]);
    }
}
