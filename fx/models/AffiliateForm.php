<?php
namespace fx\models;

use api\models\V1\Affiliate;
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
    public $province;
    public $city;
    public $district;
    public function __construct($config = [])
    {
        if($model = Affiliate::findOne(['status'=>1,'customer_id'=>Yii::$app->user->getId()])){
            $this->mode = $model->mode;
            $this->type = $model->type;
            $this->rebate_type = $model->rebate_type;
            $this->name = $model->name;
            $this->username = $model->username;
            $this->description = $model->description;
            $this->contact_name = $model->contact_name;
            $this->address = $model->address;
            $this->province = $model->zone_name;
            $this->city = $model->city_name;
            $this->district = $model->district_name;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name','username','description', 'contact_name','address','type','rebate_type'], 'safe'],
            [['mode'],'ValidateMode']
        ];
    }
    public function ValidateMode($attribute, $params){
        if($this->mode){
            if($this->mode == 'DOWN_LINE'){
                if(!$this->address){
                    $this->addError('mode', '收货地址不能为空!');
                }
            }
        }
    }
    public function submit(){
        if ($this->validate()) {
            if($model = Affiliate::findOne(['customer_id'=>Yii::$app->user->getId()])){
            }else{
                $model = new Affiliate();
                $model->customer_id = Yii::$app->user->getId();
            }
            $model->mode = $this->mode;
            $model->type = $this->type;
            $model->rebate_type = $this->rebate_type;
            $model->name = $this->name;
            $model->username = $this->username;
            $model->description = $this->description;
            $model->contact_name = $this->contact_name;
            $model->address = $this->address;
            $model->status = 1;
            $model->save();
            return true;
        }
    }
}