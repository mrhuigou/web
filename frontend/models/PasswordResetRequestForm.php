<?php
namespace frontend\models;

use api\models\V1\Customer;
use api\models\V1\VerifyCode;
use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $telephone;
    public $email;
    public $verifyCode;
    public $checkcode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['telephone','verifyCode','checkcode'], 'filter', 'filter' => 'trim','on'=>'telephone'],
            ['telephone','match','pattern'=>'/^1[34578]\d{9}$/','on'=>'telephone'],
            [['telephone','verifyCode','checkcode'], 'required','on'=>'telephone'],
            ['telephone','validateTelephone','on'=>'telephone'],
            ['checkcode','validateCheckcode','on'=>'telephone'],
            [['email','verifyCode'],'filter', 'filter' => 'trim','on'=>'email'],
            [['email','verifyCode'], 'required','on'=>'email'],
            ['email','email', 'on'=>'email'],
            ['email','validateEmail', 'on'=>'email'],
            ['verifyCode', 'captcha','message'=>'图片验证码不正确','on'=>['email']],
        ];
    }
    public function scenarios()
    {
        return [
            'telephone' => ['telephone','verifyCode','checkcode'],
            'email' => ['email','verifyCode'],
        ];
    }
    public function validateTelephone($attribute, $params){
        if(!$model=User::findByUsername($this->telephone)){
            $this->addError($attribute,'此手机用户不存在！');
        }
    }
    public function validateEmail($attribute, $params){
        if(!$model=User::findByUsername($this->email)){
            $this->addError($attribute,'此邮箱用户不存在！');
        }
    }
    public function validateCheckcode($attribute, $params){
        $model=VerifyCode::findOne(['phone'=>$this->telephone,'status'=>0,'code'=>$this->checkcode]);
        if(!$model){
            $this->addError($attribute,'语音验证码不正确！');
        }else{
            $model->status=1;
            $model->update();
        }
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function submit()
    {
        if($this->validate()){
            $user=User::findByUsername($this->telephone?$this->telephone:$this->email);
            if ($user) {
                if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                    $user->generatePasswordResetToken();
                    $user->save();
                }
                return $user;
            }
        }
            return false;

    }

    public function attributeLabels(){
        return ['telephone'=>'手机号码',
                  'email'=>'邮箱地址',
                 'verifyCode' => '图片验证码',
                'checkcode'=>'验证码',
                ];
    }
}
