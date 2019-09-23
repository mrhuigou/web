<?php
namespace common\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class SecuritySetPasswordForm extends Model
{
    public $old_password;
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
            ['password', 'required'],
            [['password'], 'string', 'min' => 6, 'max' => 20],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password','message'=>'请再次输入新密码'],
            ['old_password', 'required'],
            ['old_password', 'validateoldpassword'],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        if($this->validate()){
            $user = User::findOne(['customer_id'=>Yii::$app->user->getId()]);
            $salt = substr(md5(uniqid(rand(), true)), 0, 9);
            $user->password = sha1($salt . sha1($salt . sha1($this->password)));
            $user->salt=$salt;
            $user->update();
            //$user->removePasswordResetToken();
            return true;
        }else{
            return false;
        }

    }
    public function validateoldpassword($attribute){
        $user = User::findOne(['customer_id'=>Yii::$app->user->getId()]);
        $password_hash=sha1($user->salt . sha1($user->salt . sha1($this->old_password)));
        if($user->password==$password_hash){
            return true;
        }else{
            $this->addError($attribute,'原密码不正确！');
        }
    }
    public function attributeLabels(){
        return ['old_password'=>'原密码',
            'password'=>'新密码',
            'password_repeat' => '重复密码',
        ];
    }
}
