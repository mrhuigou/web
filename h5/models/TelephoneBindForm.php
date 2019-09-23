<?php
namespace h5\models;
use api\models\V1\CustomerAuthentication;
use api\models\V1\VerifyCode;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class TelephoneBindForm extends Model
{
	public $telephone;
	public $verifyCode;
	public $authentication_model;
	public function __construct($authentication_model=null, $config = [])
	{
		$this->authentication_model=$authentication_model;
		parent::__construct($config);
	}
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['telephone','verifyCode'], 'filter', 'filter' => 'trim'],
			[['telephone','verifyCode'], 'required'],
			['telephone', 'string', 'length' => 11],
			['verifyCode', 'string', 'min' => 4],
			['verifyCode', 'checkcode'],
		];
	}
	public function checkcode($attribute, $params){
		if($model=VerifyCode::findOne(['phone'=>$this->telephone,'code'=>$this->verifyCode,'status'=>0])){
			$model->status=1;
			$model->update();
		}else{
			$this->addError($attribute,'验证码不正确！');
		}
	}
	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 */
	public function save()
	{
		if ($this->validate()) {
			if(!$user=User::findOne(['telephone'=>$this->telephone])){
				$user=User::findIdentity(Yii::$app->user->getId());
				$user->telephone = $this->telephone;
				$user->telephone_validate=1;
				$user->save();
			}else{
				$user->telephone_validate=1;
				$user->save();
			}
			if($this->authentication_model){
				if($auto_model=CustomerAuthentication::findOne($this->authentication_model->customer_authentication_id)){
					$auto_model->customer_id=$user->customer_id;
					$auto_model->save();
				}
			}
			Yii::$app->user->login($user, 3600 * 24 * 7);
			\Yii::$app->cart->loadFromLogin();
			return $user;
		}
		return null;
	}
	public function attributeLabels(){
		return [
				'telephone'=>'手机号码',
				'verifyCode'=>'语音验证码'
				];
	}
}
