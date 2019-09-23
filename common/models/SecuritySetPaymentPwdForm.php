<?php
namespace common\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class SecuritySetPaymentPwdForm extends Model
{
    public $password;
    public $password_repeat;

    /**
     * @var \common\models\User
     */
    //private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    // public function __construct($token, $config = [])
    // {
    //     if (empty($token) || !is_string($token)) {
    //         throw new InvalidParamException('Password reset token cannot be blank.');
    //     }
    //     $this->_user = User::findByPasswordResetToken($token);
    //     if (!$this->_user) {
    //         throw new InvalidParamException('Wrong password reset token.');
    //     }
    //     parent::__construct($config);
    // }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password','password_repeat'], 'required'],
            [['password','password_repeat'], 'string', 'length' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    { if($this->validate()){
        $user = User::findIdentity(Yii::$app->user->getId());
        if($user){
	if(empty($user->salt) || empty($user->password)){
            $user->setPassword("weinxin@365jiarun");
        }
            $user->setPayPassword($this->password);
            $user->update();
            return true;
        }
        }
        return false;

    }

    public function attributeLabels(){
        return [
            'password'=>'新密码',
            'password_repeat' => '重复密码',
        ];
    }
}
