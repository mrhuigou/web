<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/1/18
 * Time: 17:41
 */
namespace api\controllers\MiniProgram;

use api\models\V1\CustomerAuthentication;
use common\component\Curl\Curl;
use \yii\rest\Controller;
class LoginController extends  Controller{
    public function actionCodeRequest(){
        $code = \Yii::$app->request->get("code");
        $data = [];
        $data['appid'] = 'wxb500456a0475600b';//arvinzhang@mrhuigou.com 小程序appid 用于测试
        $data['secret'] = '7c94df6236d651e80dff81aa03b7dba2';
        $data['js_code'] = $code;
        $data['grant_type'] = 'authorization_code';
        $url="https://api.weixin.qq.com/sns/jscode2session";
        $curl=new Curl();
        $curl->setUserAgent("Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        $result=$curl->post($url,$data);
        $data = json_decode($result);
        if(!isset($result->error_code)){

            \Yii::$app->session->set('sessioin_key',$data->session_key);
            $identifier = isset($data->unionid) ? $data->unionid : $data->openid;
            $customer_authentication = CustomerAuthentication::findOne(['identifier'=>$identifier]);
            if(!$customer_authentication){

            }

        }



    }
}
