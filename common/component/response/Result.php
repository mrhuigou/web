<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/4
 * Time: 16:56
 */
namespace common\component\response;
use yii\helpers\Json;

class Result {
    static function Sucess($data){
        return Json::decode(['status'=>'OK','data'=>$data,'datetime'=>time()]);
    }
    static function Fail($error="",$data="",$error_code="500"){
        return Json::decode(['status'=>'Fail','data'=>$data,$error=>"",'error_code'=>$error_code,'datetime'=>time()]);
    }
    static function Error($message="",$code=500){
        exit(Json::encode(['status'=>'FAIL','error_code'=>$code,'error_message'=>$message]));
    }
    static function OK($data=[]){
        return ['status'=>'OK','data'=>$data];
    }
    static function NO($data=[]){
        return ['status'=>'NO','msg'=>$data];
    }
}