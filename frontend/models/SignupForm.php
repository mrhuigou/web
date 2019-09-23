<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;
use api\models\V1\VerifyCode;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $telephone;
    public $email;
    public $password;
    public $password_repeat;
    public $verifycode;
    public $checkcode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password','password_repeat','verifycode'],'required','on'=>['signup','signupemail']],
            [['telephone','checkcode'],'required','on'=>'signup'],
            ['telephone', 'filter', 'filter' => 'trim','on'=>'signup'],
            ['telephone','match','pattern'=>'/^1[34578]\d{9}$/','on'=>['signup']],
            ['telephone', 'unique', 'targetClass' => '\common\models\User', 'message' => '该手机已注册过了','on'=>'signup'],
            ['telephone', 'string', 'min' => 11, 'max' => 11,'on'=>'signup'],
            ['email', 'required','on' => 'signupemail'],
            ['email', 'filter', 'filter' => 'trim','on'=>'signupemail'],
            ['email', 'email','on'=>'signupemail'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '该邮箱已注册过了','on'=>'signupemail'],
            ['verifycode', 'captcha','on'=>['signupemail']],
            [['password','password_repeat'], 'string', 'min' => 6,'on'=>['signup','signupemail']],
            ['password_repeat', 'compare','compareAttribute'=>'password','message'=>'两次密码必须一致','on'=>['signup','signupemail']],
            ['checkcode','validateCheckcode','on'=>'signup']

        ];
    }

    public function scenarios()
    {
        return [
            'signup' => ['telephone', 'password','password_repeat','verifycode','checkcode'],
            'signupemail' => ['email', 'password','password_repeat','verifycode'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            $model=new User();
            if($this->telephone){
                $model->telephone=$this->telephone;
                $model->telephone_validate=1;
            }else{
                $model->email=$this->email;
            }
            $model->setPassword($this->password);
            $model->generateAuthKey();
            $model->nickname="无名小卒".rand();
            $model->customer_group_id=1;
            $model->status=1;
            $model->approved=1;
            $model->ip=\Yii::$app->getRequest()->getUserIP();
            $model->source_from="web";
            $model->date_added=date('Y-m-d H:i:s',time());
	        $model->user_agent=Yii::$app->request->getUserAgent();
            $model->save();
            return $model;
        }
        return null;
    }

    public function attributeLabels(){
        return ['username'=>'用户名',
            'email'=>'邮箱',
            'telephone'=>'手机号',
            'password'=>'密码',
            'password_repeat'=>'确认密码',
            'verifycode'=>'验证码',
            'checkcode'=>'语音验证码',
        ];
    }
    public function validateCheckcode($attribute, $params)
    {
        $model=VerifyCode::findOne(['phone'=>$this->telephone,'status'=>0,'code'=>$this->checkcode]);
        if(!$model){
            $this->addError($attribute,'语音验证码不正确！');
        }else{
            $model->status=1;
            $model->update();
        }
    }
}
