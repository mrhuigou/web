<?php

namespace api\controllers\club\v1;

use api\models\V1\AppCustomer;
use api\models\V1\ClubEvents;
use api\models\V1\ClubGroup;
use api\models\V1\Customer;
use api\models\V1\CustomerLevel;
use common\component\Helper\Helper;
use common\component\image\Image;
use common\component\Message\Sms;
use dosamigos\qrcode\QrCode;

class CustomerController extends \yii\rest\Controller
{
    public function actionIndex()
    {

        $value = "11111";//二维码内容
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小


//生成二维码图片
        QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize, 2);exit;

        //return $this->render('index');
    }
    public function actionGetqccode(){

        $cid = \Yii::$app->request->get("cid");
        $type = \Yii::$app->request->get("type_id");
        $text = "type_id=".$type."&customer_id=".$cid;
        $level = 'L';             // 纠错级别
        $size = 5;                // 大小
        QRcode::png($text,false , $level, $size);exit;
    }
    public function actionGetcustomer(){
        $customer_id = \Yii::$app->request->post("customer_id");
       // $customer_id = 41536;
        $customer = Customer::find()->where(["customer_id"=>$customer_id])->one();
        $result['photo'] = Image::resize($customer->photo,0,0);
        $result['gender'] = isset($customer->gender) ? $customer->gender : '保密';
        $result['firstname'] = isset($customer->firstname) ? $customer->firstname : '匿名';
        $result['nickname'] = isset($customer->nickname) ? $customer->nickname : '匿名';

        $level_id = isset($customer->customer_level_id) && !empty($customer->customer_level_id) ? $customer->customer_level_id : 1;

        $customer_level = CustomerLevel::findOne($level_id);
        $result['customer_level'] = $customer_level->name;

        $customer_group_num = ClubGroup::find()->where(['customer_id' => $customer_id])->count();
        $result['customer_group_num'] = $customer_group_num;

        $customer_event_num = ClubEvents::find()->where(['by_customer_id' => $customer_id])->count();
        $result['customer_event_num'] = $customer_event_num;

        $result['idcard_validate'] = isset($customer->idcard_validate) ? $customer->idcard_validate : 0;
        $result['business_validate'] = isset($customer->authen_business) ? $customer->authen_business : 0;

        $command = \Yii::$app->db->createCommand("SELECT SUM(points) AS total FROM jr_club_customer_energy WHERE customer_id=".$customer_id);
        $customer_energy = $command->queryOne();
        $command = \Yii::$app->db->createCommand("SELECT SUM(points) AS total FROM jr_club_customer_shell WHERE customer_id=".$customer_id);
        $customer_shell = $command->queryOne();


        $result['energy'] = isset($customer_energy['total']) ? $customer_energy['total'] : strval(0);
        $result['shell'] = isset($customer_shell['total']) ? $customer_shell['total'] : strval(0) ;
        $age = date("Y") - date("Y",strtotime($customer->birthday));
        $result['age'] = $age;

        $hostinfo = \Yii::$app->request->hostInfo;
        $qc_code = $hostinfo."/club/v1/customer/getqccode?cid=".$customer_id."&type_id=5";
        $result['qc_code'] = $qc_code;

        return $result;
    }
    public function actionIfexists($phone){
        $count = 0;
        $count = Customer::find()->where(["telephone"=>$phone])->count();
        if($count>0){
            return false;
        }else{
            return true;
        }
    }
    public function actionValidatetelephone(){

    }
    public function actionSendcode(){
        $phone = \Yii::$app->request->post("phone");
        if(self::actionIfexists($phone)){
            $msg['msg'] = "exists";
            return $msg;
        }else{
            $validatecode=rand(10000,99999);
            $session = \Yii::$app->session;
           // $session->set()
            $massage = "欢迎注册每日惠购，您本次操作的验证码为：".$validatecode.";";
            if(Sms::send($phone,$massage)){
                return $msg['msg'] = "success";
            }

        }

    }
    public function actionAppcustomer(){
        $token = \Yii::$app->request->post("token");
        $user_id = \Yii::$app->request->post("user_id");
        $channel_id = \Yii::$app->request->post("channel_id");
        $device_type = \Yii::$app->request->post("device_type");
        if(!empty($token)) {
            $customer_id = substr($token, 0, strpos($token, '|'));
            $model = Customer::findOne($customer_id);
            if(!empty($model)){
                $app_customer_model = AppCustomer::find()->where(['open_id'=>$customer_id])->orderBy('date_modified')->one();
                if(empty($app_customer_model)){
                    $app_customer_model = new AppCustomer();
                    $app_customer_model->device_type = $device_type;
                    $app_customer_model->user_id = $user_id;
                    $app_customer_model->channel_id = $channel_id;
                    $app_customer_model->open_id = $customer_id;
                    $app_customer_model->date_added = date("Y-m-d H:i:s");
                    $app_customer_model->date_modified = date("Y-m-d H:i:s");
                    $app_customer_model->save();
                }
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

}
