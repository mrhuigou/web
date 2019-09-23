<?php
namespace h5\models;

use api\models\V1\AffiliateCustomer;
use api\models\V1\VerifyCode;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignuptelForm extends Model
{
    public $telephone;
    public $password;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['telephone', 'filter', 'filter' => 'trim'],
            ['telephone', 'required'],
            ['telephone', 'unique', 'targetClass' => '\common\models\User', 'message' => '此手机号已经注册过了！'],
            ['telephone', 'string', 'length' => 11],
            ['telephone','match','pattern'=>'/^1[3456789]{1}\d{9}$/'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
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
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->nickname="JR".time();
            $user->telephone = $this->telephone;
            $user->telephone_validate=1;
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
        return ['telephone'=>'手机号不能为空',
            'password'=>'密码',
            'verifyCode'=>'手机验证码'
        ];
    }
}
