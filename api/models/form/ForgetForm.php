<?php
namespace api\models\form;

use api\models\V1\VerifyCode;
use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class ForgetForm extends Model
{
    public $username;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'checkUser'],
            ['verifyCode', 'required'],
            ['verifyCode', 'string', 'length' => 6],
            ['verifyCode', 'checkCode'],
        ];
    }
    public function checkUser($attribute){
        if(!$model = User::findByUsername($this->username)){
            $this->addError($attribute,'此用户不存在！');
        }
    }
    public function checkcode($attribute){
        if($model=VerifyCode::findOne(['phone'=>$this->username,'code'=>$this->verifyCode,'status'=>0])){
            $model->status=1;
            $model->update();
        }else{
            $this->addError($attribute,'验证码不正确！');
        }
    }
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function save()
    {
        if ($this->validate()) {
            /* @var $user User */
            $user = User::findByUsername($this->username);
            if ($user) {
                if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                    $user->generatePasswordResetToken();
                }
                $user->save();
            }
            return $user;
        } else {
            return false;
        }
    }
    public function attributeLabels(){
        return ['username'=>'用户名',
            'verifyCode'=>'验证码'
        ];
    }
}
