<?php
namespace h5\models;

use api\models\V1\Customer;
use api\models\V1\VerifyCode;
use common\component\Helper\Helper;
use common\component\Message\Sms;
use common\models\User;
use yii\base\Model;
use Yii;


/**
 * RealNameForm
 */
class ValidatePhoneForm extends Model
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
            //['telephone', 'unique', 'targetClass' => '\common\models\User', 'message' => '此手机号已经认证过了！'],
            ['telephone', 'string', 'length' => 11],
            ['telephone','match','pattern'=>'/^1[3456789]{1}\d{9}$/'],
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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function bindName()
    {
        if ($this->validate()) {
            $model = Customer::find()->where(['telephone'=>$this->telephone])->one();
            if(empty($model) || !$model->telephone){ //新用户直接注册 并登陆
                $password = "jr".rand(10000,99999);
                $user = new User();
                $user->nickname="JR".time();
                $user->telephone = $this->telephone;
                $user->telephone_validate=1;
                $user->status=1;
                $user->approved=1;
                $user->customer_group_id=1;
                $user->setPassword($password);
                $user->generateAuthKey();
                $user->save();
                $message = "感谢您注册家润慧生活，您的账号为".$this->telephone.",密码为".$password."。";
                Sms::send($this->telephone,$message);
                Yii::$app->user->login($user, 3600 * 24 * 7); //登陆
                return true;
            }else{ // 老用户
                Yii::$app->user->login($model, 3600 * 24 * 7); //登陆
                return true;
            }

        }else{
            return false;
        }

    }
    public function attributeLabels(){
        return ['telephone'=>'手机号不能为空',
            'verifyCode'=>'手机验证码'
        ];
    }
}
