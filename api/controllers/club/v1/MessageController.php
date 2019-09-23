<?php

namespace api\controllers\club\v1;

use api\models\V1\ClubEventsMember;
use api\models\V1\ClubGroupMember;
use api\models\V1\ClubMessage;
use api\models\V1\ClubRelation;
use api\models\V1\PushMessage;
use common\component\Helper\Helper;
use common\component\image\Image;

class MessageController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    //
    public function actionSystemmessages(){
        $customer_id = \Yii::$app->request->post("customer_id");
       // $customer_id = 314;

        $messages = ClubMessage::find()->where(['to_customer_id'=>$customer_id,'is_del'=> 0,'is_system'=>1])->orderBy("post_time DESC")->all();
        if(!empty($messages)){
            $result_data = array();
            foreach($messages as $key => $message){
               // print_r($message->content);exit;
                if(!empty($message->content)){
                    $from_customer = Helper::getCustomerformat($message->from_customer_id);
                    $content = unserialize($message->content);
                   // $type_name_id = $content['type_name_id'];F
                   // $item_id = $content['item_id'];

                    if(is_null($message->need_verify)){
                        $need_verfy = 0;
                    }else{
                        $need_verfy = $message->need_verify;
                    }
                    if(is_null($message->is_agree) ){
                        $is_agree = 0;
                    }else{
                        $is_agree = $message->is_agree;
                    }
                    $result_data[$key]['message_id'] = $message->message_id;
                    $result_data[$key]['need_verfy'] = $need_verfy;
                    $result_data[$key]['is_agree'] = $is_agree;
                    $result_data[$key]['dowhat'] = $content;
                    $result_data[$key]['who'] = $from_customer;

                }
            }
            return $result_data;
        }else{
            return array();
        }

    }
    public function  actionAnswermessage(){
        //$type_name_id = \Yii::$app->request->post("type_id");
       // $item_id = \Yii::$app->request->post("item_id");

        $message_id = \Yii::$app->request->post("message_id");

        //$customer_id = \Yii::$app->request->post("customer_id");
        $is_agree = \Yii::$app->request->post("is_agree");

        $message = ClubMessage::findOne($message_id);
        $message->is_agree = $is_agree;
        $message->save();

        if($message->need_verify == 1){
            if(!empty($message->content)){

                $content = unserialize($message->content);
                if($content['type_name_id'] == 18){ //同意加为好友
                    $this->run('/club/v1/contacts/agreefriend',array('customer_id'=>$message->from_customer_id,'to_customer_id'=>$message->to_customer_id));
                   // $model->actionAgreefriend($message->from_customer_id,$message->to_customer_id);
                }elseif($content['type_name_id'] == 4){ //同意加入活动
                    $this->run('/club/v1/events/agreeevent',array($message->to_customer_id,$content['item_id']));
                    $this->run('/club/v1/events/agreeevent',array($message->from_customer_id,$content['item_id']));
                }elseif($content['type_name_id'] == 3){//同意加入圈子
                    $this->run('/club/v1/group/agreegroup',array($message->to_customer_id,$content['item_id']));
                    $this->run('/club/v1/group/agreegroup',array($message->from_customer_id,$content['item_id']));
                }
            }
        }

        $msg['msg'] = "success";
        return $msg;
    }
    public function actionPushmessages(){
        $token = \Yii::$app->request->post("token");
        if(!empty($token)){
            $customer_id =  substr($token,0,strpos($token,'|'));

            $model = PushMessage::find()->where(['or',['and','customer_id='.$customer_id,'range_type=7'],['and','customer_id=0','range_type=1']])->orderBy("date_added DESC");
            $messages = $model->all();
            if(!empty($messages)){
                foreach($messages as $key => $message){
                    if(!empty($message['image'])){
                        $messages[$key]['image'] = Image::resize($message['image'],0,0,9);
                    }
                    if(empty($message['device_type'])){
                        $messages[$key]['device_type'] = "";
                    }
                }
//\Yii::$app->response->isOk;
                return $messages;
            }else{
                return array();
            }

        }else{
            return false;
        }

    }












}
