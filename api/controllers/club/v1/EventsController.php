<?php

namespace api\controllers\club\v1;

use api\models\V1\ClubComment;
use api\models\V1\ClubEvents;
use api\models\V1\ClubEventsFee;
use api\models\V1\ClubEventsMember;
use api\models\V1\ClubExperience;
use api\models\V1\ClubGroup;
use api\models\V1\ClubGroupMember;
use api\models\V1\ClubMessage;
use api\models\V1\ClubRelation;
use api\models\V1\Customer;
use common\component\Helper\Helper;
use common\component\image\Image;

class EventsController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    /*
  * 获取 活动详情
  * */
    public function  actionGeteventdetail(){
        $events_id = \Yii::$app->request->post('aid');
       // $events_id = 11;
        $customer_id = \Yii::$app->request->post("customer_id");

        if(!empty($events_id)){

            $events_info = ClubEvents::find()->where( ['events_id'=>$events_id])->asArray()->one();

            $images = array();
            if(isset($events_info['image_array']) && !empty($events_info['image_array'])){
                $image_array = unserialize($events_info['image_array']);
                if(is_array($image_array)){
                    foreach($image_array as $k => $img){
                        $images[$k]['image'] =  Image::resize($img['image'],0,0);
                    }
                }else{
                    $images = array();
                }
            }
            $events_info['image_array'] = $images;
            $events_info['cover_image'] = Image::resize($events_info['cover_image'],0,0);
            $events_info['customer'] = Helper::getCustomerformat($events_info['by_customer_id']);
            /**七个成员信息*/
            $members = array();
            $member_status = "-1";//不是圈子成员，并且未请求过加入圈子
            $event_member = ClubEventsMember::find()->where(['events_id'=>$events_info['events_id']])->orderBy("created_at DESC")->offset(0)->limit(7)->all();
            $members = [];
            foreach($event_member as $member){
                if($member->customer_id == $customer_id){
                    if($member->status == 1){
                        $members[] = Helper::getCustomerformat($member->customer_id) ;
                        $member_status = "1";//已经是圈子成员
                    }else{
                        $member_status = "0"; //已经请求了加入圈子，没有同意
                    }
                }
            }
            $events_info['event_member'] = $members;
            $events_info['count_member'] = strval(count($members));
            if($events_info['has_fee']){
                $total = 0;
                $event_fee = ClubEventsFee::find()->where(['events_id'=>$events_info['events_id']])->all();
                //print_r($event_fee);exit;
                foreach($event_fee as $fee){
                    $total = $total + $fee['price'];
                }
                $events_info['fee_total'] = strval($total);
            }else{
                $events_info['fee_total'] = "0";
            }
            /**5条体验信息*/
            $exps = array();
            $event_exps = ClubExperience::find()->where(['event_id' => $events_info['events_id'],'is_del'=>'0'])->orderBy("last_update_time DESC")->offset(0)->limit(5)->asArray()->all();
            if(!empty($event_exps)){
                foreach($event_exps as $key => $exp){

                    $images = array();
                    if(!empty($exp['image_array'])){
                        $image_array = unserialize($exp['image_array']);
                        if(is_array($image_array)){
                            foreach($image_array as $k => $img){
                                $images[$k]['image'] = Image::resize($img['image'],0,0);
                                //$images[$k]['des'] = Image::resize($img['des'],0,0);
                            }
                        }else{
                            $images = array();
                        }
                    }
                    $exps[$key]['customer'] = Helper::getCustomerformat($exp['customer_id']);
                    $exps[$key]['cover_image'] = Image::resize($exp['cover_image'],0,0);
                    $exps[$key]['image_array'] = $images;
                    $exps[$key]['num_comment'] = ClubComment::find()->where(["type_name_id"=>1,"content_id"=>$exp['exp_id']])->count();
                }
            }
            $events_info['event_exps'] = $exps;
            /**5条评论信息*/
            $event_comments = array();
            $event_comments = ClubComment::find()->where(['type_name_id'=>4,'content_id'=>$events_info['events_id']])->orderBy("create_time DESC")->offset(0)->limit(5)->asArray()->all();
            foreach($event_comments as $key =>$comment){
                $event_comments[$key]['customer'] = Helper::getCustomerformat($comment['customer_id']);
            }
            $event_comments = Helper::genTree($event_comments,'comment_id','reference_id','children');
            $events_info['event_comments'] = $event_comments;

            $events_info['assemble_time'] = isset($events_info['assemble_time']) ? $events_info['assemble_time'] : "";
            $events_info['assemble_address'] = isset($events_info['assemble_address']) ? $events_info['assemble_address'] : "";
            $events_info['member_status'] = $member_status;
            $events_info['status'] = strval($events_info['status']);
            $hostinfo = \Yii::$app->request->hostInfo;
            $qc_code = $hostinfo."/club/v1/customer/getqccode?cid=".$events_id."&type_id=4";
            $events_info['qr_code'] = $qc_code;
            return $events_info;
        }else{
            return "error";
        }

    }
    public function actionGeteventmember(){
        $events_id = \Yii::$app->request->post("aid");

        $events_members = ClubEventsMember::find()->where(['events_id'=>$events_id,'status'=>1])->all();
        if(!empty($events_members)){
            foreach($events_members as $customer){
                $result[] = Helper::getCustomerformat($customer->customer_id);
            }
            return $result;
        }else{
            return "";
        }


    }
    //加入活动
    public function actionJoinevent(){
        $event_id = \Yii::$app->request->post('aid');
        $customer_id = \Yii::$app->request->post('customer_id');
        $event_member = new ClubEventsMember();
        $event_member->events_id = $event_id;
        $event_member->customer_id = $customer_id;
        $event_member->status = 0; //0待审核  1已加入
        $event_member->created_at = date("Y-m-d H:i:s");
        $event_member->is_signin = 0; //是否已经签到
        if($event_member->save()){
            $data = [
                'type_name_id'=> 4,
                'item_id'       => $event_id,
                'action'        => "加入活动",
                'customer_id'   => $customer_id,
            ];
            //Helper::setMyPage($customer_id,$data); //我的个人主页
           // a:4:{s:12:"type_name_id";i:3;s:7:"item_id";i:20;s:7:"content";s:9:"自驾游";s:2:"do";s:15:"邀请你加入";}
            $event = ClubEvents::findOne($event_id);
            $msgdata = [
                'from_group_id' => $event->by_group_id,
                'from_events_id' => $event->events_id,
                'need_verify' =>  1,
                'is_system'  => 1,
                'content'   => array(
                        'type_name_id' => 4,
                        'item_id'   => $event_id,
                        'content'   => $event->title,
                        'do'        => "申请加入"
                ),
            ];
            Helper::setMessage($customer_id,$event->by_customer_id,$msgdata);

            return "success";
        }else{
            return "error";
        }
    }
//同意加入圈子
    public function actionAgreeevent($customer_id,$event_id){

        $relation = ClubEventsMember::find()->where(["customer_id"=>$customer_id,"events_id"=>$event_id])->one();
        if(!empty($relation)){
            $relation->status = 1;
            $relation->save();
        }else{
            $model = new ClubEventsMember();
            $model->customer_id = $customer_id;
            $model->event_id = $event_id;
            $model->status = 1;
            $model->created_at = date("Y-m-d H:i:s");
            $model->save();
        }
        $data = [];
        $data['type_name_id'] = 3;
        $data['item_id'] = $event_id;
        $data['action'] = "加入活动";
        $data['customer_id'] = $customer_id;

        Helper::setMyPage($customer_id,$data);

        $myfriends = ClubRelation::find()->where(['customer_id'=> $customer_id ])->all();
        foreach($myfriends as $friend){
            Helper::setTrends($friend->friend_customer_id,$data); //告知我的朋友们，我创建了某个活动
        }
    }

    //创建活动
    public function actionInsertevents(){
        /**/
        $title = \Yii::$app->request->post('topic');
        $event_type = \Yii::$app->request->post('event_type');
       // $event_type = 1;
        $cover_image = \Yii::$app->request->post('image');
        $by_group_id = \Yii::$app->request->post('circle');
        $by_customer_id = \Yii::$app->request->post('customer_id');
        $sign_end_time = \Yii::$app->request->post('limittime') ; //报名截止时间
        $start_time = \Yii::$app->request->post('starttime');
        $end_time = \Yii::$app->request->post('endtime');//活动结束时间
        $address = \Yii::$app->request->post('address');
        $fee = \Yii::$app->request->post('cost'); //活动费用
        $member_limit = \Yii::$app->request->post('memcount');
        $member_gender = \Yii::$app->request->post('sex');
        $description = \Yii::$app->request->post('content');

        $inviteFri = \Yii::$app->request->post('inviteFri');
        $inviteFri = trim($inviteFri,",");
        $inviteFri = explode(",",$inviteFri);
        $inviteSMS = \Yii::$app->request->post('inviteSMS'); //发送短信邀请注册
        $inviteSMS = trim($inviteSMS,",");
        $inviteSMS = explode(",",$inviteSMS);


        $model_event = new ClubEvents();
        $model_event->title = $title;
        $model_event->events_type = $event_type;
        $model_event->cover_image = $cover_image;
        $model_event->by_customer_id = $by_customer_id;
        $model_event->by_group_id = intval($by_group_id);
        $model_event->sign_end_time = $sign_end_time;
        $model_event->start_time = $start_time;
        $model_event->end_time = $end_time;
        $model_event->address = $address;
        $model_event->member_limit = $member_limit;
        $model_event->member_gender = $member_gender;
        $model_event->description = $description;
        $model_event->created_at = date("Y-m-d H:i:s");
        $model_event->save();
       // $error = $model_event->errors;
        if($fee > 0){
            $model_event->has_fee = 1;
        }else{
            $model_event->has_fee = 0;
        }
        $event_id = $model_event->events_id;


        $event_member = new ClubEventsMember();
        $event_member->events_id = $event_id;
        $event_member->customer_id = $by_customer_id;
        $event_member->status = 1;
        $event_member->created_at = date("Y-m-d H:i:s");
        $event_member->save();
        if(!empty($fee)){

            $event_fee = new ClubEventsFee();
            $event_fee->events_id = $event_id;
            $event_fee->price = $fee;
            $event_fee->save();
        }


        // 设置redis 动态数据
        $data = [
            'type_name_id'=> 4,
            'item_id'   => $event_id,
            'customer_id' => $by_customer_id,
            'group_id'  => $by_group_id, //与圈子相关
            'action'    => '创建活动',
        ];

        Helper::setTrends($by_customer_id,$data);

        Helper::setMyPage($by_customer_id,$data); //我的个人主页
        //ClubRelation::find()->where()->all();
        //$myfriends = ClubRelation::find()->where(['customer_id'=> $by_customer_id ])->all();
       // foreach($myfriends as $friend){
       //     Helper::setTrends($friend->friend_customer_id,$data); //告知我的朋友们，我创建了某个活动
       // }
        // 设置redis 动态数据

        $message_content = array(
            'type_name_id'  => 4,
            'item_id'   => $event_id,
            'content'   => $title,
            'do'        => "邀请你加入"
        );


        if(isset($inviteFri) && !empty($inviteFri)){
            foreach($inviteFri as $customer_id){ //发送站内信
                $model_message = new ClubMessage();
                $model_message->from_customer_id = $by_customer_id;
                $model_message->to_customer_id = $customer_id;
                $model_message->content = serialize($message_content);
                $model_message->is_read = 0;
                $model_message->post_time = date("Y-m-d H:i:s");
                $model_message->is_del = 0;
                $model_message->is_system = 1;
                $model_message->need_verify = 1;
                $model_message->save();
            }
        }
        if(isset($inviteSMS) && !empty($inviteSMS)){
            foreach($inviteSMS as $telephone){
                $customer = Customer::find()->where(['telephone'=>$telephone ])->asArray()->one();

                if(!empty($customer)){
                    $model_message = new ClubMessage();
                    $model_message->from_customer_id = $by_customer_id;
                    $model_message->to_customer_id = $customer['customer_id'];
                    $model_message->content = serialize($message_content);
                    $model_message->is_read = 0;
                    $model_message->post_time = date("Y-m-d H:i:s");
                    $model_message->is_del = 0;
                    $model_message->is_system = 1;
                    $model_message->need_verify = 1;
                    $model_message->save();
                }else{

                    $msg = "您的好友邀请您加入活动".$title."。快来注册吧！";
                    Helper::Sendsmqxt($telephone,$msg);
                }
            }
        }

        $msg = array();
        $msg['events_id'] = strval($event_id);
        return $msg;
    }
    //邀请加入圈子(type = 3)或者活动（type=4）
    public function actionInvitefriends(){
        $customer_id = \Yii::$app->request->post("customer_id");
        $item_id = \Yii::$app->request->post('item_id');
        $type_name_id = \Yii::$app->request->post('type_id');
        $inviteFri = \Yii::$app->request->post('inviteFri');
        $inviteFri = trim($inviteFri,",");
        $inviteFri = explode(",",$inviteFri);
        if(self::action_Invitefriends($customer_id,$type_name_id,$item_id,$inviteFri)){
            return "success";
        }else{
            return "error";
        }
    }
    //邀请加入圈子(type = 3)或者活动（type=4）
    public function action_Invitefriends($customer_id,$type_name_id,$item_id,$inviteFri){

        $title = "";
        if(!empty($item_id) && $type_name_id == 3){
            $model = ClubGroup::findOne($item_id);
            //$model_member = ClubGroupMember::find();
            $title = $model->title;
        }
        if(!empty($item_id) && $type_name_id == 4){
            $model = ClubEvents::findOne($item_id);
           // $model_member = ClubEventsMember::find();
            $title = $model->title;
        }

        $message_content = array(
            'type_name_id'  => $type_name_id,
            'item_id'   => $item_id,
            'content'   => $title,
            'do'        => "邀请你加入"
        );
        if(isset($inviteFri) && !empty($inviteFri)){
            foreach($inviteFri as $c_id){ //发送站内信
                if(!empty($item_id) && $type_name_id == 3){
                    $count = ClubGroupMember::find()->where(['customer_id'=>$c_id,'group_id'=>$item_id])->count();
                    if($count == 0 ){
                        $model_member = new ClubGroupMember();
                        $model_member->customer_id = $c_id;
                        $model_member->group_id = $item_id;
                        $model_member->status = 0;
                        $model_member->created_at = date("Y-m-d H:i:s");
                        $model_member->save();
                    }

                }
                if(!empty($item_id) && $type_name_id == 4){
                    $count = ClubEventsMember::find()->where(['customer_id'=>$c_id,'events_id'=>$item_id])->count();
                    if($count == 0 ){
                        $model_member = new ClubEventsMember();
                        $model_member->customer_id = $c_id;
                        $model_member->events_id = $item_id;
                        $model_member->status = 0;
                        $model_member->created_at = date("Y-m-d H:i:s");
                        $model_member->save();
                    }
                }


                $model_message = new ClubMessage();
                $model_message->from_customer_id = $customer_id;
                $model_message->to_customer_id = $c_id;
                $model_message->content = serialize($message_content);
                $model_message->is_read = 0;
                $model_message->post_time = date("Y-m-d H:i:s");
                $model_message->is_del = 0;
                $model_message->is_system = 1;
                $model_message->need_verify = 1;
                $model_message->is_agree = 0;
                $model_message->save();
            }
        }
        return true;
    }
    public function actionGetmycreateevent(){
        $customer_id = \Yii::$app->request->post("customer_id");
        //$customer_id = 314;
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);
        $data['where'] = ["by_customer_id"=>$customer_id];
        $data['orderby'] = 'created_at DESC';
        $data['offset'] = isset($count[0]) ? $count[0] : 0;
        $data['limit'] = isset($count[1]) ? $count[1] : 20;

        $model = ClubEvents::find();
        $myenents = $this->actionFormatdata($model,$data);
        $results = array();
        if(!empty($myenents)){
            foreach($myenents as $key => $event_info){

                $cover_image = Image::resize( $event_info['cover_image'],100,100);
                $member_num = ClubEventsMember::find()->where(["events_id"=>$event_info['events_id']])->count(); //成员数量
                $experience_num = ClubExperience::find()->where(["event_id" => $event_info['events_id']])->count(); //体验数量
                $results[] = array(
                    'events_id' => $event_info['events_id'],
                    'title'     => $event_info['title'],
                    'cover_image'=> $cover_image,
                    'member_num' => $member_num,
                    'exp_num'   => $experience_num
                );
            }
        }
        return $results;
    }
    public function actionGetmyjoinevents(){
        $customer_id = \Yii::$app->request->post("customer_id");
        //$customer_id = 314;
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);
        $data['where'] = ["customer_id"=>$customer_id];
        $data['orderby'] = 'created_at DESC';
        $start = isset($count[0]) && !empty($count[0]) ? $count[0] : 0;
        $limit = isset($count[1]) ? $count[1] : 20;

        //$events = ClubEvents::find()->joinWith('members')->where([ClubEventsMember::tableName().'.customer_id'=>$customer_id,'by_customer_id'=>$customer_id])->groupBy("events_id")->asArray()->offset($data['offset'])->limit($data['limit'])->all();
       $sql = "SELECT `jr_club_events`.* FROM `jr_club_events` LEFT JOIN `jr_club_events_member` ON `jr_club_events`.`events_id` = `jr_club_events_member`.`events_id` WHERE `jr_club_events_member`.customer_id=".$customer_id." AND jr_club_events.by_customer_id <>".$customer_id." GROUP BY `events_id`  limit ".$start." ,".$limit;
        $command = \Yii::$app->db->createCommand($sql);
        $events = $command->queryAll();

        $results = array();
        if(!empty($events)){
            foreach($events as $event_info){
                $cover_image = Image::resize( $event_info['cover_image'],100,100);
                $member_num = ClubEventsMember::find()->where(["events_id"=>$event_info['events_id']])->count(); //成员数量
                $experience_num = ClubExperience::find()->where(["event_id" => $event_info['events_id']])->count(); //体验数量
                if($customer_id != $event_info['by_customer_id']){
                    $results[] = array(
                        'events_id' => $event_info['events_id'],
                        'title'     =>$event_info['title'],
                        'cover_image'=> $cover_image,
                        'member_num' => $member_num,
                        'exp_num'   => $experience_num
                    );
                }
            }
        }

        return $results;
    }
    /*
     * 格式化数据；
      */
    private function actionFormatdata($model,$data){
        if(isset($data['limit']) && intval($data['limit']) > 0){
            $pagesize = $data['limit'];
        }else{
            $pagesize = 10;
        }
        if(isset($data['offset']) && intval($data['offset']) > 0){
            $offset = $data['offset'];
        }else{
            $offset = 0;
        }

        $model = $model->offset($offset)->limit($pagesize);
        if(isset($data['orderby']) && !empty($data['orderby'])){
            $model =  $model->orderby($data["orderby"]);
        }
        if(isset($data['where']) && !empty($data['where'])){
            $model = $model->where($data["where"]);
        }
        $model = $model->asArray()->all();

        return  $model;
    }
    public function bindActionParams($action, $params){
        return $params;
    }


}
