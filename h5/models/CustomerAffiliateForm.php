<?php

namespace h5\models;

use api\models\V1\CustomerAffiliate;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class CustomerAffiliateForm extends Model
{
	public $username;
	public $telephone;
	public $agree=1;
	public function __construct($config = [])
	{
		if(!Yii::$app->user->isGuest){
			$this->username=Yii::$app->user->identity->firstname;
			$this->telephone=Yii::$app->user->identity->telephone;
		}
		parent::__construct($config);
	}
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['username', 'telephone','agree'],'trim'],
			[['username', 'telephone','agree'], 'required'],
			['telephone', 'string', 'length' => 11],
			['telephone','match','pattern'=>'/^1[3456789]{1}\d{9}$/'],
			['agree','compare','compareValue'=>1,'message'=>'必须同意合伙人协议']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'username' => '姓名',
			'telephone'=>'手机号',
			'agree'=>'同意'
		];
	}

	public function save()
	{
		if ($this->validate()) {
			if(!$model=CustomerAffiliate::findOne(['customer_id'=>Yii::$app->user->getId()])){
				$model=new CustomerAffiliate();
				$model->customer_id=Yii::$app->user->getId();
				$model->username=$this->username;
				$model->telephone=$this->telephone;
				$model->commission=0.015;
				$model->status=1;
				$model->date_added=date('Y-m-d H:i:s',time());
				$model->save();
			}
			return $model;
		}
		return null;
	}
}
