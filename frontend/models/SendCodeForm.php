<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/12/8
 * Time: 9:59
 */
namespace frontend\models;
use common\component\Message\Sms;
use common\component\Sms\VoiceVerify;
use yii\base\Model;
use Yii;
use api\models\V1\VerifyCode;
class SendCodeForm extends Model
{
    public $telephone;
    public $verifycode;
    public function __construct($config = []){
        parent::__construct($config);
    }
    public function rules()
    {
        return[
            [['telephone'],'required','on'=>['isGuest','noGuest']],
            ['verifycode','required','on'=>'isGuest'],
            ['telephone','match','pattern'=>'/^1[34578]\d{9}$/','on'=>['isGuest','noGuest']],
            ['verifycode', 'captcha','on'=>'isGuest'],
        ];
    }
    public function scenarios()
    {
        return [
            'isGuest' => ['telephone','verifycode'],
            'noGuest' => ['telephone'],
        ];
    }
    public function send(){
        if($this->validate()){
            if(!$model=VerifyCode::findOne(['phone'=>$this->telephone,'status'=>0])){
                $code=rand(1000,9999);
                $model=new VerifyCode();
                $model->phone=$this->telephone;
                $model->code=strval($code);
                $model->status=0;
                $model->date_added=date('Y-m-d H:i:s',time());
                $model->save();
            }
           // $message="您的家润验证码:".$model->code."，请勿将验证码泄露给其他人。";
            $voice=new VoiceVerify();
            if($voice->send($this->telephone,$model->code)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }



    public function attributeLabels(){
        return [
            'telephone'=>'手机号',
            'verifycode'=>'验证码',
        ];
    }

}