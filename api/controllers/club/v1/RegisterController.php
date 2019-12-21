<?php

namespace api\controllers\club\v1;

use api\models\V1\Customer;
use api\models\V1\VerifyCode;
use common\component\Helper\Helper;
use common\component\Message\Sms;

class RegisterController extends \yii\rest\Controller
{

    public function actionSubmit(){
        $data=\Yii::$app->request->post();
        $this->validateform($data);
        if(!$model=Customer::findOne(['telephone'=>$data['username']])){
            $model=new Customer();
            $model->telephone=$data['username'];
            $model->setPassword($data['password']);
            $model->nickname="无名小卒";
            $model->customer_group_id=1;
            $model->telephone_validate=1;
            $model->status=1;
            $model->approved=1;
            $model->ip=\Yii::$app->getRequest()->getUserIP();
            $model->source_from="app";
            $model->save();
            return ['message'=>'注册成功','status'=>"1"];
        }else{
            return ['message'=>'用户名重复','status'=>"0"];
        }

    }
    private function validateform($data){
        if(!isset($data['username']) || empty($data['username'])){
            throw new \yii\web\HttpException(400, "Post username is must and not empty");
        }
        if(!isset($data['password']) || empty($data['password'])){
            throw new \yii\web\HttpException(400, "Post password is must and not empty");
        }
    }
    public function actionSendcode(){
        $data=\Yii::$app->request->post();
        if(isset($data['username']) && $data['username']){
            if(!$model=VerifyCode::findOne(['phone'=>$data['username'],'status'=>0])){
                $code=rand(100000,999999);
                $model=new VerifyCode();
                $model->phone=strval($data['username']);
                $model->code=strval($code);
                $model->status=0;
                $model->date_added=date('Y-m-d H:i:s',time());
                $model->save();
            }
            $message="您的每日惠购验证码:".$model->code."，请勿将验证码泄露给其他人。";
            if(Sms::send($data['username'],$message)){
                return ['message'=>'发送成功','status'=>"1"];
            }else{
                return ['message'=>'发送失败','status'=>"0"];
            }

        }else{
            throw new \yii\web\HttpException(400, "Post username is must and not empty");
        }
    }
    public function actionCheckcode(){
        $data=\Yii::$app->request->post();
        if($this->validatecheckcode($data)){
            $model=VerifyCode::findOne(['phone'=>$data['username'],'status'=>0]);
            $model->status=1;
            $model->update();
            return ['message'=>'验证成功','status'=>"1"];

        }else{
            return ['message'=>'验证失败','status'=>"0"];
        }
    }
    private function validatecheckcode($data){
        if(!isset($data['username']) || empty($data['username'])){
            throw new \yii\web\HttpException(400, "Post username is must and not empty");
        }
        if(!isset($data['checkcode']) || empty($data['checkcode'])){
            throw new \yii\web\HttpException(400, "Post checkcode is must and not empty");
        }
        $checkcode=VerifyCode::findOne(['phone'=>$data['username'],'status'=>0]);
        return $data['checkcode']!=$checkcode?$checkcode->code:'';
    }

}
