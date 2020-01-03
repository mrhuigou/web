<?php
/**
 * Created by PhpStorm.
 * User: 22095
 * Date: 2020/1/3
 * Time: 17:25
 */

namespace h5\models;


use yii\base\Model;
use api\models\V1\Address;
use common\models\User;
use yii\base\InvalidParamException;
use Yii;

class ViewDeliveryForm extends  Model
{
    public $username;
    public $telephone;
    public $poiname;
    public $poiaddress;
    public $address;
    public $postcode = 266000;
    public $lat;
    public $lng;
    public $is_default = 1;
    public $address_id = 0;
    public $has_other_zone;
    public $province;
    public $city;
    public $district;
    public $in_range = 1; //1在服务范围内，只能选市内四区；0可能超出服务范围
    public $total;
    public $limit_min_quantity;
    public $coupon_id;
    public function __construct($config = [])
    {
        if (\Yii::$app->session->get('checkout_address_id')) {
            $this->address_id = \Yii::$app->session->get('checkout_address_id');
        }else{
            if(\Yii::$app->user->identity->address_id){
                if($address=Address::findOne(\Yii::$app->user->identity->address_id)){
                    if($address->ifInRange){
                        $this->address_id =\Yii::$app->user->identity->address_id;
                    }else{
                        User::updateAll(['address_id'=>0],['customer_id'=>Yii::$app->user->getId()]);
                    }
                }else{
                    //更新
                    User::updateAll(['address_id'=>0],['customer_id'=>Yii::$app->user->getId()]);
                }
            }else{
                if($address=Address::findOne(['customer_id'=>Yii::$app->user->getId()])){
                    if($address->ifInRange){
                        $this->address_id=$address->address_id;
                    }
                }
            }
        }


        $this->telephone=Yii::$app->user->identity->telephone;
        if ($model = Address::findOne(['address_id' => $this->address_id, 'customer_id' => Yii::$app->user->getId()])) {
            $this->address_id = $model->address_id;
            $this->username = $model->firstname;
            $this->telephone = $model->telephone;
            $this->postcode = $model->postcode;
            $this->poiname = $model->poiname;
            $this->poiaddress=$model->poiaddress;
//			if($model->poiname && strpos($model->address_1,$model->poiname) !== false){
//				$this->address =$model->address_1;
//			}else{
//				$this->address = $model->poiaddress.$this->poiname.$model->address_1;
//			}
            $this->address =$model->address_1;
            $this->province=$model->zone?$model->zone->name:"山东省";
            $this->city=$model->citys?$model->citys->name:"青岛市";
            $this->district=$model->district?$model->district->name:"请选择";
            $this->lat = $model->lat;
            $this->lng = $model->lng;
        }else{
            $this->province="山东省";
            $this->city="青岛市";
            $this->district="请选择";
        }
        $this->has_other_zone=false;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['province','city','district','address','username','telephone','is_default'], 'required'],
            [['province','city','district','address','username','telephone','is_default'], 'safe'],
            [['address_id', 'delivery'], 'safe'],
//            [['address_id', 'delivery'], 'FormValidate'],
            //[['invoice_type'],'ValidateInvoice']
        ];
    }

    public function attributeLabels()
    {
        return [
            'address' => '详细地址',
            'username' => '收货人',
            'telephone'=>'手机号',
        ];
    }

    public function submit(){

    }
}