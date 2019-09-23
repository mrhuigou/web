<?php
namespace h5\models;

use api\models\V1\AffiliateCustomer;
use api\models\V1\CustomerAuthentication;
use api\models\V1\CustomerFollower;
use api\models\V1\VerifyCode;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class AutoLoginForm extends Model
{
	public $telephone;
	public $verifyCode;
	public $authentication_model;
	public function __construct($authentication_model, $config = [])
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
				if($this->authentication_model->customer_id){
					$user = User::findIdentity($this->authentication_model->customer_id);
					$user->firstname=$this->authentication_model->display_name;
					$user->nickname=$this->authentication_model->display_name;
					$user->telephone = $this->telephone;
					$user->telephone_validate=1;
					$user->gender=$this->authentication_model->gender;
					$user->photo=$this->authentication_model->photo_url;
					$user->user_agent=Yii::$app->request->getUserAgent();
					$user->save();
				}else{
					$user = new User();
					$user->firstname=$this->authentication_model->display_name;
					$user->nickname=$this->authentication_model->display_name;
					$user->telephone = $this->telephone;
					$user->telephone_validate=1;
					$user->gender=$this->authentication_model->gender;
					$user->photo=$this->authentication_model->photo_url;
					$user->status=1;
					$user->approved=1;
					$user->customer_group_id=1;
					$user->setPassword('weinxin@365jiarun');
					$user->generateAuthKey();
					$user->user_agent=Yii::$app->request->getUserAgent();
					$user->date_added=date('Y-m-d H:i:s',time());
                    if($affiliate_id=\Yii::$app->session->get('from_affiliate_uid')){
                        $user->affiliate_id=$affiliate_id;
                    }
					$user->save();
				}
				if($share_user_id=Yii::$app->session->get('source_from_uid')){
					if(User::findIdentity($share_user_id)){
						if(!$auth=CustomerFollower::findOne(['follower_id'=>$user->getId()])){
							$customer_share_user=new CustomerFollower();
							$customer_share_user->customer_id=$share_user_id;
							$customer_share_user->follower_id=$user->customer_id;
							$customer_share_user->status=0;
							$customer_share_user->creat_at=time();
							$customer_share_user->save();
						}
					}
				}
			}
			if($auto_model=CustomerAuthentication::findOne($this->authentication_model->customer_authentication_id)){
				$auto_model->customer_id=$user->customer_id;
				$auto_model->save();
			}
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
