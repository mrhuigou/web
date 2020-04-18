<?php
namespace fx\models;

use api\models\V1\Affiliate;
use api\models\V1\District;
use common\component\Curl\Curl;
use common\component\Helper\Map;
use common\component\Helper\RandomString;
use Yii;
class AffiliateForm extends Affiliate {
    public $mode = 'DOWN_LINE';
    public $rebate_type = 'product';
    public $type = 'personal';
    public $name;
    public $username;
    public $description;
    public $contact_name;
    public $address;
    public $zone_name;
    public $city_name;
    public $district_name;
    public $lng;
    public $lat;
    public $agree=1;
    public $in_range=1;
    public $has_other_zone;
    public $telephone;
    public function __construct($mode='DOWN_LINE',$config = [])
    {
//        if($model = Affiliate::findOne(['status'=>1,'customer_id'=>Yii::$app->user->getId()])){
//            $this->mode = $model->mode;
//            $this->type = $model->type;
//            $this->rebate_type = $model->rebate_type;
//            $this->name = $model->name;
//            $this->username = $model->username;
//            $this->description = $model->description;
//            $this->contact_name = $model->contact_name;
//            $this->address = $model->address;
//            $this->province = $model->zone_name;
//            $this->city = $model->city_name;
//            $this->district = $model->district_name;
//        }
            $this->mode = Yii::$app->request->post()['AffiliateForm']['mode']?:$mode;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name','username','description','telephone','contact_name','type','rebate_type','zone_name','city_name','district_name','address'], 'safe'],
            [['name','telephone','contact_name','type','rebate_type'], 'required'],
//            [['mode'],'ValidateMode']
            ['telephone', 'string', 'length' => 11],
            [['address'],'poiValidate'],
            [['telephone'], 'ValidateTelephone'],
        ];
    }
    public function ValidateTelephone($attribute, $params){
        if($this->telephone){
            if($affiliate = Affiliate::findOne(['telephone'=>$this->telephone])){
                $this->addError('telephone', '此号码已经注册过了!');
            }
        }
    }

    public function poiValidate($attribute, $params){
        $center_lat=36.1516;
        $center_lng=120.39822;
        if(!$this->address) {
            $this->addError($attribute,'请输入小区/写字楼/学校/街道等');
        }
        if($this->district_name == '请选择' || empty($this->district_name)){
            $this->addError($attribute,'您的地址错误，请选择区域');
        }
        $curl=new Curl();
        $url='http://apis.map.qq.com/ws/geocoder/v1/';
        $result=$curl->get($url,['address'=>$this->city_name.$this->district_name.$this->address,'key'=>'GNWBZ-7FSAR-JL5WY-WIDVS-FHLY2-JVBEC']);
        if($result && $result->status==0 && $result->result){
            $this->lat=$result->result->location->lat;
            $this->lng=$result->result->location->lng;
            if($result->result->address_components->province){
                $this->zone_name=trim($result->result->address_components->province);
            }
            if($result->result->address_components->city){
                $this->city_name=trim($result->result->address_components->city);
            }
//			if($result->result->address_components->district){
//				$this->district=trim($result->result->address_components->district);
//			}
//            if(!$this->poiname){
//                $this->poiname=$result->result->title;
//            }
//            if(!$this->poiaddress){
//                $this->poiaddress=$result->result->address_components->street.$result->result->address_components->street_number;
//            }
        }else{
            $this->addError($attribute,'您输入的地址不在配送范围之内!');
        }
        if($this->in_range == 1){
            if($this->lat && $this->lng){
                if($this->has_other_zone){
                    if(($distance=Map::GetShortDistance($center_lng,$center_lat,$this->lng,$this->lat))>35*1000){
                        $this->addError($attribute,'您输入的地址超出配送范围！');
                    }
                }else{
                    if(($distance=Map::GetShortDistance($center_lng,$center_lat,$this->lng,$this->lat))>15*1000){
                        $this->addError($attribute,'您输入的地址超出配送范围！');
                    }
                }
            }
            if($this->zone_name && !in_array($this->zone_name,['山东省'])){
                $this->addError($attribute,'['.$this->zone_name.']'.'超出配送范围，请重新选择！');
            }
            if($this->city_name && !in_array($this->city_name,['青岛市'])){
                $this->addError($attribute,'['.$this->city_name.']'.'超出配送范围，请重新选择！');
            }
            // $district_array=['市南区','市北区','四方区','李沧区','崂山区','黄岛区'];
            $active_districts = District::find()->select('name')->where(['is_use'=>1])->all();
            foreach ($active_districts as $active_district){
                $district_array[] = $active_district->name;
            }
            if($this->district_name && !in_array($this->district_name,$district_array)){
                $this->addError($attribute,'['.$this->district_name.']'.'超出配送范围，请重新选择！');
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'username' => '展示名称',
            'description'=>'描述',
            'contact_name'=>'联系人',
            'telephone'=>'联系电话',
            'agree'=>'同意',
            'mode'=>'模式',
            'type'=>'类型',
            'rebate_type'=>'返佣类型',
            'address'=>'详细地址',

        ];
    }


    public function submit(){
        if ($this->validate()) {
            if($model = Affiliate::findOne(['customer_id'=>Yii::$app->user->getId()])){
            }else{
                $model = new Affiliate();
                $model->customer_id = Yii::$app->user->getId();
            }
            $model->code = 'af'.RandomString::random_text('alnum',6);
            $model->mode = strtoupper($this->mode);
            $model->type = strtoupper($this->type);
            $model->rebate_type = strtoupper($this->rebate_type);
            $model->name = $this->name;
            $model->username = $this->username?:$this->name;
            $model->description = $this->description;
            $model->contact_name = $this->contact_name;
            $model->zone_name = $this->zone_name;
            $model->city_name = $this->city_name;
            $model->district_name = $this->district_name;
            $model->address = $this->address;
            $model->telephone = $this->telephone;
            $model->setPassword('123456'); //默认密码123456
            $model->lng = $this->lng;
            $model->lat = $this->lat;
            $model->status = 0;
            $model->save();

            $return_data = array(
                'CODE'=>$model->code,
                'NAME'=>$model->name,
                'DISPLAY_NAME'=>$model->username,
                'TYPE'=>$model->type,
                'MODE'=>$model->mode,
                'REBATE_TYPE'=>$model->rebate_type,
                'DESCRIPTION'=>$model->description,
                'CONTACT_NAME'=>$model->contact_name,
                'TELEPHONE'=>$model->telephone,
            );
            if($model->mode == 'DOWN_LINE'){
                $return_data['ADDRESS'] = $model->address;
            }
            //自动同步后台
            if($return_data){
                $erp_wsdl = Yii::$app->params['ERP_SOAP_URL'];
                $client = new \SoapClient($erp_wsdl, array('soap_version' => SOAP_1_1, 'exceptions' => false));
                $data=$this->getParam('applyAffiliate',array($return_data));
                $content = $client->getInterfaceForJson($data);
                $result=$this->getResult($content);
                Yii::error(json_encode($result));
                if($result['status']=='OK'){
                    $model->send_status = 1;
                    $model->save();
                }
            }



            return true;
        }
    }

    //生成请求数据方法
    protected function getParam($a,$d=array(),$v='1.0'){
        $t=time();
        $m='webservice';
        $key='asdf';
        $data=array('a'=>$a,'c'=>'NONE','d'=>$d,'f'=>'json','k'=>md5($t.$m.$key),'m'=>$m,'l'=>'CN','p'=>'soap','t'=>$t,'v'=>$v);
        return json_encode($data);
    }

    //获取结果数据方法
    protected function getResult($data){
        $result=json_decode($data,true);
        return $result;
    }
}