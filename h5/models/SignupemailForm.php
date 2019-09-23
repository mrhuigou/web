<?php
namespace h5\models;

use api\models\V1\AffiliateCustomer;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupemailForm extends Model
{
    public $email;
    public $password;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '此邮箱址已经注册过了！'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->nickname="JR".time();
            $user->email = $this->email;
            $user->email_validate=1;
            $user->status=1;
            $user->approved=1;
            $user->customer_group_id=1;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->date_added=date('Y-m-d H:i:s',time());
	        $user->user_agent=Yii::$app->request->getUserAgent();
            if($affiliate_id=\Yii::$app->session->get('from_affiliate_uid')){
                $user->affiliate_id=$affiliate_id;
            }
            $user->save();
            return $user;
        }
        return null;
    }
    public function attributeLabels(){
        return [
            'email'=>'邮箱地址',
            'password'=>'密码',
            'verifyCode'=>'验证码'
        ];
    }
}
