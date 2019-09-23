<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/14
 * Time: 17:32
 */
namespace backend\models\form;
use api\models\V1\WeixinKefu;
use yii\base\Model;
class WeixinAccountForm extends Model {
	public $account;
	public $nickname;
	public $password;
	public $isNewRecord;
	public function __construct($id = 0, $config = [])
	{
		if ($id && $model = WeixinKefu::findOne(['id' => $id])) {
			$this->account=$model->kf_account;
			$this->nickname = $model->kf_nick;
			$this->password = $model->kf_password;
			$this->isNewRecord=false;
		}else{
			$this->isNewRecord=true;
		}
		parent::__construct($config);
	}
	public function rules()
	{
		return [
			[['account','nickname','password'], 'required'],
			['account', 'string'],
			['nickname', 'string'],
			['password', 'string'],
		];
	}
	public function save()
	{
		if ($this->validate()) {
			if($this->isNewRecord){
				$model=new WeixinKefu();
			}
			$model->kf_account=$this->account."@qingdaojiarun";
			$model->kf_nick=$this->nickname;
			$model->kf_password=$this->password;
			$model->save();
			return $model;
		}
		return null;
	}
	public function attributeLabels()
	{
		return [
			'account' => '客服帐户',
			'nickname'=>'昵称',
			'password'=>'密码'
		];
	}
}