<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/11
 * Time: 10:50
 */
namespace h5\models;
use yii\base\Model;
use Yii;
class ExpressForm extends Model {
	public $address_id;
	public $remark;
	public $products;
	public $id;
	public function __construct($id, $config = [])
	{
		if(!$this->address_id=Yii::$app->session->get('checkout_address_id')){
			$this->address_id=Yii::$app->user->identity->address_id;
		}
		parent::__construct($config);
	}
	public function rules(){
		return [
			[['address_id','remark'], 'filter', 'filter' => 'trim'],
			[['address_id'], 'required'],
			['id','checkCard']
		];
	}
	public function checkCard($attribute, $params){

	}
	public function save()
	{
		if ($this->validate()) {

		}
	}
}