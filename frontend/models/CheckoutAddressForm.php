<?php
namespace frontend\models;

use api\models\V1\Address;
use api\models\V1\City;
use api\models\V1\District;
use api\models\V1\RechargeHistory;
use api\models\V1\Zone;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Signup form
 */
class CheckoutAddressForm extends Model
{   public $firstname;
    public $telephone;
    public $zone_id=119;
    public $city_id=10848;
    public $district_id;
    public $address;
    public $lat;
    public $lng;
    public $postcode=266000;
    public $is_default=true;
    private $_address;
    public $has_other_zone;
    public function __construct($address_id=0,$config = [])
    {
         $this->_address = Address::findOne(['customer_id'=>Yii::$app->user->getId(),'address_id'=>$address_id]);
        if($this->_address){
            $this->firstname=$this->_address->firstname;
            $this->telephone=$this->_address->telephone;
            $this->zone_id=$this->_address->zone_id;
            $this->city_id=$this->_address->city_id;
            $this->district_id=$this->_address->district_id;
            $this->address=$this->_address->address_1;
            $this->postcode=$this->_address->postcode;
            $this->lat=$this->_address->lat;
            $this->lng=$this->_address->lng;
        }
        $this->has_other_zone=$this->getIsHisense(Yii::$app->user->getId());
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['telephone', 'filter', 'filter' => 'trim'],
            [['telephone','firstname','address','zone_id','city_id','district_id'], 'required'],
            ['telephone', 'string', 'length' => 11],
            ['firstname', 'string', 'min' => 2],
            ['address', 'string', 'min' => 3],
            ['postcode', 'string', 'length' => 6],
            [['is_default','lat','lng'], 'number'],
        ];
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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            if(!$model=$this->_address) {
                $model = new Address();
            }
            $model->customer_id=Yii::$app->user->getId();
            $model->firstname=htmlspecialchars($this->firstname);
            $model->country_id=854;
            $model->zone_id=119;
            $model->city_id=10848;
            $model->district_id=$this->district_id;
            $model->telephone = htmlspecialchars($this->telephone);
            $model->address_1= htmlspecialchars($this->address);
            $model->postcode=htmlspecialchars($this->postcode);
            $model->lat=htmlspecialchars($this->lat);
            $model->lng=htmlspecialchars($this->lng);
            $model->date_added=date('Y-m-d H:i:s',time());
            $model->save();
            return $model;
        }
        return null;
    }
    public function attributeLabels(){
        return ['firstname'=>'收货人',
            'telephone'=>'手机号',
            'zone_id'=>'所在省份',
            'city_id'=>'所在市级',
            'district_id'=>'所在区域',
            'address'=>'街道地址',
            'postcode'=>'邮政编码'
        ];
    }

}
