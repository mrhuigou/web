<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/17
 * Time: 11:36
 */
namespace h5\models;
use yii\base\Model;
use Yii;
class CheckoutShippingForm extends Model
{
    public $shipping_date;
    public $shipping_time;
    public $delivery;
    public $delivery_time;
    public $time=['1'=>'08:00-12:00','2'=>'12:00-18:00','3'=>'18:00-22:00'];
    public function __construct($config = [])
    {
        if(time()>strtotime(date('Y-m-d 17:00:00'))){
            $this->shipping_date=1;
            $this->shipping_time=1;
        }else{
            $this->shipping_date=0;
            if(time()<strtotime(date('Y-m-d 12:00:00'))){
                $this->shipping_time=2;
            }else{
                $this->shipping_time=3;
            }
        }
        $this->delivery=date('Y-m-d', strtotime('+ ' . $this->shipping_date . ' day'));
        $this->delivery_time=$this->time[$this->shipping_time];
        parent::__construct($config);
    }
    public function rules()
    {
        return [
            [['shipping_date','shipping_time'], 'required'],
            [['shipping_date','shipping_time'], 'validateDate'],
        ];
    }

    public function attributeLabels(){
        return ['shipping_date'=>'配送日期',
                'shipping_time'=>'配送时间',
                 ];
    }
    public function validateDate($attribute, $params){

        if($this->shipping_date){
            if($this->shipping_date>6){
                $this->addError('shipping_date','送达日期超出范围（限7日内）');
            }
        }else{
            if($this->shipping_time==1 && time()>strtotime(date('Y-m-d 00:00:00'))){
                $this->addError('shipping_time','此配送时间段已过,请选择下一个时间段');
            }
            if($this->shipping_time==2 && time()>strtotime(date('Y-m-d 12:00:00'))){
                $this->addError('shipping_time','此配送时间段已过,请选择下一个时间段');
            }
            if($this->shipping_time==3 && time()>strtotime(date('Y-m-d 17:00:00'))){
                $this->addError('shipping_time','此配送时间段已过,请选择下一个时间段');
            }
        }
    }



    public function save(){

        if ($this->validate()) {
            Yii::$app->session->set('checkout_delivery', date('Y-m-d', strtotime('+ ' . $this->shipping_date . ' day')));
            Yii::$app->session->set('checkout_delivery_time', $this->time[$this->shipping_time]);
            return true;
        }
        return false;
    }

}