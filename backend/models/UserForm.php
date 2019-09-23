<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/12
 * Time: 13:33
 */
namespace backend\models;
use Yii;
use yii\base\Model;

class UserForm extends Model {
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	public $user_id;
	public $username;
	public $email;
	public $password;
	public $status=1;
	public $firstname;
	public $lastname;
	public $password_repeat;
	public $isNewRecord=false;

	public function __construct($user_id=0,$config = [])
	{
		if($model=User::findIdentity($user_id)){
			$this->user_id=$model->user_id;
			$this->username=$model->username;
			$this->email=$model->email;
			$this->status=$model->status;
			$this->firstname=$model->firstname;
			$this->lastname=$model->lastname;
		}else{
			$this->isNewRecord=true;
		}
		parent::__construct($config);
	}

	public function rules()
	{
		return [
			[['firstname','lastname','username', 'email'], "required"],
			['username', 'unique', 'targetClass' => '\backend\models\User', 'message' => '用户名已注册过了'],
			['email', 'email'],
			[['password', 'password_repeat'], 'string', 'min' => 6],
			['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码必须一致'],
			['status', 'default', 'value' => self::STATUS_ACTIVE],
			['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
		];
	}

	public function attributeLabels()
	{
		return ['username' => '用户名',
			'email' => '邮箱',
			'firstname' => '姓名',
			'lastname' => '部门',
			'password' => '密码',
			'password_repeat' => '确认密码',
			'status' => '状态',
		];
	}
	public function save(){
		if($this->validate()){
			if(!$model=User::findIdentity($this->user_id)){
				$model=new User();
				$model->generateAuthKey();
			}
			$model->firstname=$this->firstname;
			$model->lastname=$this->lastname;
			$model->username=$this->username;
			$model->email=$this->email;
			$model->status=$this->status;
			if($this->password){
				$model->setPassword($this->password);
			}
			$model->date_added=date('Y-m-d H:i:s',time());
			$model->save();
			return $model;
		}
		return null;
	}
}