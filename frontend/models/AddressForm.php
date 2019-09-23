<?php
namespace frontend\models;
use api\models\V1\Address;
use api\models\V1\City;
use api\models\V1\District;
use api\models\V1\RechargeHistory;
use api\models\V1\Zone;
use common\models\User;
use common\component\Curl\Curl;
use common\component\Helper\Map;
use yii\base\Model;
use Yii;

/**
 * AddressForm
 */
class AddressForm extends Model {
    public $username;
    public $telephone;
    public $poiname;
    public $poiaddress;
    public $address;
    public $postcode = 266000;
    public $lat;
    public $lng;
    public $is_default = 1;
    public $address_id;
    public $has_other_zone;
    public $province;
    public $city;
    public $district;
    public function __construct($address_id = 0, $config = [])
    {
        $this->telephone=Yii::$app->user->identity->telephone;
        if ($model = Address::findOne(['address_id' => $address_id, 'customer_id' => Yii::$app->user->getId()])) {
            $this->address_id = $model->address_id;
            $this->username = $model->firstname;
            $this->telephone = $model->telephone;
            $this->postcode = $model->postcode;
            $this->poiname = $model->poiname;
            $this->poiaddress=$model->poiaddress;
//            if($model->poiname && strpos($model->address_1, $this->poiname) !== false){
//                $this->address =$model->address_1;
//            }else{
//                $this->address = $model->poiaddress.$this->poiname.$model->address_1;
//            }
            $this->address =$model->address_1;
            $this->province=$model->zone?$model->zone->name:"山东省";
            $this->city=$model->citys?$model->citys->name:"青岛市";
            $this->district=$model->district?$model->district->name:"市南区";
            $this->lat = $model->lat;
            $this->lng = $model->lng;
        }else{
            $this->province="山东省";
            $this->city="青岛市";
            $this->district="市南区";
        }
        $this->has_other_zone=false;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['telephone', 'username','province','city','district','poiname','poiaddress', 'address', 'postcode'], 'filter', 'filter' => 'trim'],
            [['username', 'telephone','address'], 'required'],
            ['telephone', 'string', 'length' => 11],
            ['username', 'string', 'min' => 1,'max'=>20],
            ['address', 'string', 'min' => 1,'max'=>255],
            [['address'],'poiValidate'],
            [['postcode'], 'string', 'length' => 6],
            [['is_default','lat','lng'],'safe']
        ];
    }
    public function poiValidate($attribute, $params){
        $center_lat=36.1516;
        $center_lng=120.39822;
        if(!$this->address) {
            $this->addError($attribute,'请输入小区/写字楼/学校/街道等');
        }
        $curl=new Curl();
        $url='http://apis.map.qq.com/ws/geocoder/v1/';
        $result=$curl->get($url,['address'=>'青岛市'.$this->address,'key'=>'GNWBZ-7FSAR-JL5WY-WIDVS-FHLY2-JVBEC']);
        if($result && $result->status==0 && $result->result){
            $this->lat=$result->result->location->lat;
            $this->lng=$result->result->location->lng;
            if($result->result->address_components->province){
                $this->province=trim($result->result->address_components->province);
            }
            if($result->result->address_components->city){
                $this->city=trim($result->result->address_components->city);
            }
            if($result->result->address_components->district){
                $this->district=trim($result->result->address_components->district);
            }
            if(!$this->poiname){
                $this->poiname=$result->result->title;
            }
            if(!$this->poiaddress){
                $this->poiaddress=$result->result->address_components->street.$result->result->address_components->street_number;
            }
        }else{
            $this->addError($attribute,'您输入的地址不在配送范围之内!');
        }
        if($this->lat && $this->lng){
            if($this->has_other_zone){
                if(($distance=Map::GetShortDistance($center_lng,$center_lat,$this->lng,$this->lat))>30*1000){
                    $this->addError($attribute,'您输入的地址超出配送范围！');
                }
            }else{
                if(($distance=Map::GetShortDistance($center_lng,$center_lat,$this->lng,$this->lat))>15*1000){
                    $this->addError($attribute,'您输入的地址超出配送范围！');
                }
            }
        }
        if($this->province && !in_array($this->province,['山东省'])){
            $this->addError($attribute,'['.$this->province.']'.'超出配送范围，请重新选择！');
        }
        if($this->city && !in_array($this->city,['青岛市'])){
            $this->addError($attribute,'['.$this->city.']'.'超出配送范围，请重新选择！');
        }
        $district_array=['市南区','市北区','四方区','李沧区','崂山区'];
        if($this->district && !in_array($this->district,$district_array)){
            $this->addError($attribute,'['.$this->district.']'.'超出配送范围，请重新选择！');
        }
    }
    public function getIsHisense($customer_id){
        $status=false;
        $model=RechargeHistory::find()->where(['customer_id'=>$customer_id])->all();
        if($model){
            foreach($model as $value){
                if($value->rechargeCard && strtolower($value->rechargeCard->card_code)==strtolower('Hisense') ){
                    $status=true;
                    break;
                }
            }
        }
        return $status;
    }
    /**
     * AddressForm
     *
     * @return address|null the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            if (!$model = Address::findOne($this->address_id)) {
                $model = new Address();
                $model->date_added = date('Y-m-d H:i:s', time());
            }
            $model->customer_id = Yii::$app->user->getId();
            $model->firstname = $this->username;
            $model->telephone = $this->telephone;
            $model->poiname=$this->poiname;
            $model->poiaddress=$this->poiaddress;
            $model->address_1 = $this->address;
            $model->postcode = $this->postcode;
            $model->lat = $this->lat;
            $model->lng = $this->lng;
            $zone=Zone::findOne(['name'=>$this->province]);
            $city=City::findOne(['name'=>$this->city,'zone_id'=>$zone?$zone->zone_id:0]);
            $district=District::findOne(['name'=>$this->district,'city_id'=>$city?$city->city_id:0]);
            $model->country_id=854;
            $model->zone_id=$zone?$zone->zone_id:0;
            $model->city_id=$city?$city->city_id:0;
            $model->district_id=$district?$district->district_id:0;
            $model->date_modified = date('Y-m-d H:i:s', time());
            $model->save();
            if ($this->is_default) {
                $user = User::findIdentity(Yii::$app->user->getId());
                $user->address_id = $model->address_id;
                $user->save();
            }
            return $model;
        }
        return null;
    }

    public function attributeLabels()
    {
        return ['username' => '收货人',
            'telephone' => '手机号',
            'district' => '小区/写字楼/街道',
            'poiaddress'=>'街道地址',
            'address' => '详细地址',
            'postcode' => '邮政编码',
            'is_default'=>'默认地址'
        ];
    }
}
