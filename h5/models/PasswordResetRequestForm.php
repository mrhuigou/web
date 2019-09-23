<?php
namespace h5\models;

use api\models\V1\VerifyCode;
use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $telephone;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['telephone', 'filter', 'filter' => 'trim'],
            ['telephone', 'required'],
            ['telephone', 'string', 'length' => 11],
            ['telephone','match','pattern'=>'/^1[3456789]{1}\d{9}$/'],
            ['telephone', 'exist',
                'targetClass' => '\common\models\User',
               // 'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => '此用户不存在！'
            ],
            ['verifyCode', 'required'],
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
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'telephone' => $this->telephone,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return \Yii::$app->mailer->compose('passwordResetToken', ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . \Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
    public function attributeLabels(){
        return ['telephone'=>'手机号',
            'verifyCode'=>'手机验证码'
        ];
    }
}
