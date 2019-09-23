<?php
namespace api\controllers\club\v1;
use api\models\V1\AppCustomer;
use api\models\V1\Customer;
use api\models\V1\CustomerAuthentication;
use common\component\response\Result;
use common\models\User;



class OauthController extends \yii\rest\Controller
{
    public function actionLogin()
    {
        $open_id = \Yii::$app->request->post("openid");
        $provider = \Yii::$app->request->post("provider"); //提供方 QQ,Sina
        $nickname = \Yii::$app->request->post("nickname");
        $photo =  \Yii::$app->request->post("photo");
        $gender =  \Yii::$app->request->post("gender");

        $user_id = \Yii::$app->request->post("user_id");
        $device_type = \Yii::$app->request->post("device_type");
        $channel_id = \Yii::$app->request->post("channel_id");

        if($gender == 1){
            $gender = '男';
        }else{
            $gender = '女';
        }

        if(strtolower($provider) == 'qq'){
            $provider = "QQ";
        }elseif(strtolower($provider) == 'wx'){
            $provider = "WeiXin";
        }elseif(strtolower($provider) == 'sina'){
            $provider = "Sina";
        }elseif(strtolower($provider) == 'douban'){
            $provider = "DouBan";
        }
        $customer_auth = CustomerAuthentication::find()->where(['provider'=>$provider,'identifier'=>$open_id])->one();
        if(isset($customer_auth) && !empty($customer_auth) && isset($customer_auth->customer_id)){
           // $identity = User::findIdentity($customer_auth->customer_id);
            // User::findByUsername($this->username);
            //\Yii::$app->user->login($identity);
            $customer = Customer::find()->where(['customer_id'=> $customer_auth->customer_id] )->one();
            $customer->photo = $photo;
            $customer->save();
                $app_customer = AppCustomer::find()->where(['open_id'=>$customer->customer_id,'user_id'=>$user_id,'device_type'=>$device_type])->one();
                if($app_customer){
                    $app_customer->channel_id = $channel_id;
                    $app_customer->date_modified = date("Y-m-d H:i:s");
                    $app_customer->save();
                }else{
                    if(!empty($device_type)) {
                        if(( strtoupper($device_type) == "IOS" && !empty($user_id) && !empty($channel_id) ) ){
                            $app_customer = new AppCustomer();
                            $app_customer->device_type = $device_type;
                            $app_customer->channel_id = $channel_id;
                            $app_customer->user_id = $user_id;
                            $app_customer->open_id = $customer->customer_id;
                            $app_customer->date_added =  date("Y-m-d H:i:s");
                            $app_customer->date_modified =  date("Y-m-d H:i:s");
                            $app_customer->save();
                        }else{
                            if(strtoupper($device_type) == "ANDROID" && !empty($channel_id)){
                                $app_customer = new AppCustomer();
                                $app_customer->device_type = $device_type;
                                $app_customer->channel_id = $channel_id;
                                //$app_customer->user_id = $user_id;
                                $app_customer->open_id = $customer->customer_id;
                                $app_customer->date_added =  date("Y-m-d H:i:s");
                                $app_customer->date_modified =  date("Y-m-d H:i:s");
                                $app_customer->save();
                            }
                        }
                    }
                }
            $token = $customer_auth->customer_id."|".date("Ymd").'|'.md5($customer_auth->customer_id."customer_id");
            $data = array('token'=>$token,"telephone_validate" => $customer->telephone_validate);
            return $data;
            //return Result::OK($data);
        }else{
            $customer = new Customer();
            $customer->nickname = $nickname;
            $customer->photo = $photo;
            $customer->gender = $gender;
            $customer->setPassword(substr(rand().microtime(), 0, 6));
            //$customer->salt = strval(rand(1000,9999)); //随便生成一个，保证用户不能用账号登录，必须用qq，sina等登录
            $customer->status=1;
            $customer->approved=1;
            $customer->customer_group_id=1;
            $customer->date_added=date('Y-m-d H:i:s',time());
            $customer->save();
            if(isset($customer->customer_id)){
                if(!empty($device_type)) {
                    if(( strtoupper($device_type) == "IOS" && !empty($user_id) && !empty($channel_id) ) ){
                        $app_customer = new AppCustomer();
                        $app_customer->device_type = $device_type;
                        $app_customer->channel_id = $channel_id;
                        $app_customer->user_id = $user_id;
                        $app_customer->open_id = $customer->customer_id;
                        $app_customer->date_added =  date("Y-m-d H:i:s");
                        $app_customer->date_modified =  date("Y-m-d H:i:s");
                        $app_customer->save();
                    }else{
                        if(strtoupper($device_type) == "ANDROID" && !empty($channel_id)){
                            $app_customer = new AppCustomer();
                            $app_customer->device_type = $device_type;
                            $app_customer->channel_id = $channel_id;
                            //$app_customer->user_id = $user_id;
                            $app_customer->open_id = $customer->customer_id;
                            $app_customer->date_added =  date("Y-m-d H:i:s");
                            $app_customer->date_modified =  date("Y-m-d H:i:s");
                            $app_customer->save();
                        }
                    }
                }

                $customer_auth = new CustomerAuthentication();
                $customer_auth->customer_id = $customer->customer_id;
                $customer_auth->provider = $provider;
                $customer_auth->identifier = $open_id;
                $customer_auth->photo_url = $photo ? $photo :"";
                $customer_auth->display_name = $nickname;
                $customer_auth->gender = $gender;
                $customer_auth->date_added = date("Y-m-d H:i:s");
                $customer->status = 1;
                $customer->customer_group_id = 1;
                $customer_auth->save();
                $token = $customer->customer_id."|".date("Ymd").'|'.md5($customer->customer_id."customer_id");

                $data = array('token'=>$token,"telephone_validate" => "0");
                return $data;
                //return Result::OK($data);
            }else{
                //return "error";
                return Result::Error("error");
            }

        }
       // return $this->render('index');
    }

}
