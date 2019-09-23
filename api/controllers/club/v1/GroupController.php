<?php

namespace api\controllers\club\v1;

use api\models\V1\ClubEvents;
use api\models\V1\ClubEventsMember;
use api\models\V1\ClubExperience;
use api\models\V1\ClubGroup;
use api\models\V1\ClubGroupMember;
use api\models\V1\ClubGroupType;
use api\models\V1\ClubMessage;
use api\models\V1\ClubRelation;
use api\models\V1\Customer;
use common\component\Helper\Helper;
use common\component\image\Image;

class GroupController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    /*
   * 获取 圈子详情
   * */
    public function  actionGetgroupdetail(){
        $group_id = \Yii::$app->request->post('cid');
        $customer_id = \Yii::$app->request->post('customer_id');
        $data = [];
        $data['where'] = ['group_id'=>$group_id];
        $model = ClubGroup::find();
        $results = $this->actionFormatdata($model,$data);
        $result = [];
        $customer_member = ClubGroupMember::find()->where(['group_id'=>$group_id,'customer_id'=>$customer_id])->one();

        if(!empty($customer_member)){
            if($customer_member->status == 1){
                $member_status = "1";//已经是圈子成员
            }else{
                $member_status = "0"; //已经请求了加入圈子，没有同意
            }
        }else{
            $member_status = "-1"; //没请求过加入圈子
        }
        foreach($results as $k => $v){
            $result['group_id'] = $v['group_id'];
            if(!isset($v['group_type_id'])){
                $group_type_id = 1;
            }else{
                $group_type_id = $v['group_type_id'];
            }
            $group_type = ClubGroupType::findOne($group_type_id);
            $result['group_type']['group_type_id'] = $group_type->group_type_id;
            $result['group_type']['group_type_name'] = $group_type->group_type_name;

            $result['customer'] = Helper::getCustomerformat($v['customer_id']);

            $result['title'] = $v['title'];
            $result['member_status'] = $member_status;

            $result['description'] = $v['description'];
            $result['create_time'] = $v['created_at'];
            $result['updated_at'] = $v['updated_at'];
            $result['logo'] = Image::resize($v['logo'],0,0);
            $result['events'] = self::actionGetgroupevents($group_id);
            $hostinfo = \Yii::$app->request->hostInfo;
            $qc_code = $hostinfo."/club/v1/customer/getqccode?cid=".$group_id."&type_id=3";
            $result['qr_code'] = $qc_code;
        }
        return $result;
    }
    /*
     * 获取圈子列表
     * */
    public function actionGetgrouplist(){
        $customer_id = \Yii::$app->request->post("customer_id");
        //$customer_id = 314;
        $orderby = \Yii::$app->request->post("orderby");
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);

        $data = [];
        if(!empty($customer_id)){
            $data['where'] = ['customer_id'=>$customer_id];
        }
        if(!empty($orderby)){
            $data['orderby'] = $orderby;
        }else{
            $data['orderby'] = 'created_at DESC';
        }

        $data['offset'] = isset($count[0]) ? $count[0] : 0;
        $data['limit'] = isset($count[1]) ? $count[1] : 20;
        $model = ClubGroup::find();
        $result = $this->actionFormatdata($model,$data);
        return $result;
    }
    //某人可以加入某圈子
    public function actionAgreegroup($customer_id,$group_id){
        $relation = ClubGroupMember::find()->where(["customer_id"=>$customer_id,"group_id"=>$group_id])->one();
        if(!empty($relation)){
            $relation->status = 1;
            $relation->save();
        }else{
            $model = new ClubGroupMember();
            $model->customer_id = $customer_id;
            $model->group_id = $group_id;
            $model->status = 1;
            $model->created_at = date("Y-m-d H:i:s");
            $model->save();
        }
        //$group = ClubGroup::find()->where(['group_id'=>$group_id])->one();
        $data = [];
        $data['type_name_id'] = 3;
        $data['item_id'] = $group_id;
        $data['action'] = "加入圈子";
        $data['customer_id'] = $customer_id;

        Helper::setMyPage($customer_id,$data);

        $myfriends = ClubRelation::find()->where(['customer_id'=> $customer_id ])->all();
        foreach($myfriends as $friend){
            Helper::setTrends($friend->friend_customer_id,$data); //告知我的朋友们，我加入了某个圈子
        }
    }
    public function actionGetallgrouptype(){
        $group_types = ClubGroupType::find()->asArray()->all();
        return $group_types;
    }
    public function actionInsertgroup(){
        $customer_id = \Yii::$app->request->post("customer_id");//创建者
        $title = \Yii::$app->request->post("title");
        $description = \Yii::$app->request->post("description");
        $group_type_id = \Yii::$app->request->post("group_type_id");
        $logo = \Yii::$app->request->post("logo");

        $inviteFri = \Yii::$app->request->post('inviteFri');
        $inviteFri = trim($inviteFri,",");
        $inviteFri = explode(",",$inviteFri);
        $inviteSMS = \Yii::$app->request->post('inviteSMS'); //发送短信邀请注册
        $inviteSMS = trim($inviteSMS,",");
        $inviteSMS = explode(",",$inviteSMS);

        $group = new ClubGroup();
        $group->customer_id = $customer_id;
        $group->title = $title;
        $group->description = $description;
        $group->group_type_id = $group_type_id;
        $group->created_at = date("Y-m-d H:i:s");
        $group->updated_at = date("Y-m-d H:i:s");
        $group->logo = $logo;
        $group->status = 1;
        if($group->save()){
            $group_id = $group->group_id;
            $group_member = new ClubGroupMember();
            $group_member->customer_id = $customer_id;
            $group_member->group_id = $group_id;
            $group_member->status = 1;
            $group_member->created_at = date("Y-m-d H:i:s");
            $group_member->save();

            $data=[
                'type_name_id' => 3,
                'item_id'       => $group_id,
                'action'        => '创建圈子',
                'customer_id'     => $customer_id,
            ];

            Helper::setTrends($customer_id,$data); //加入我的动态中
            Helper::setMyPage($customer_id,$data); //加入我的个人主页中
            //$myfriends = ClubRelation::find()->where(['customer_id'=> $customer_id ])->all();
            //foreach($myfriends as $friend){
            //    Helper::setTrends($friend->friend_customer_id,$data); //告知我的朋友们，我创建了某个圈子
           // }

            $message_content = array(
                'type_name_id'  => 3,
                'item_id'   => $group_id,
                'content'   => $title,
                'do'        => "邀请你加入"
            );
            if(isset($inviteFri) && !empty($inviteFri)){
                foreach($inviteFri as $c_id){ //发送站内信
                    $model_message = new ClubMessage();
                    $model_message->from_customer_id = $customer_id;
                    $model_message->to_customer_id = $c_id;
                    $model_message->content = serialize($message_content);
                    $model_message->is_read = 0;
                    $model_message->post_time = date("Y-m-d H:i:s");
                    $model_message->is_del = 0;
                    $model_message->need_verify=1;
                    $model_message->is_system = 1;
                    $model_message->save();
                }
            }
            if(isset($inviteSMS) && !empty($inviteSMS)){
                foreach($inviteSMS as $telephone){

                    $customer = Customer::find()->where(['telephone'=>$telephone ])->asArray()->one();

                    if(!empty($customer)){
                        $model_message = new ClubMessage();
                        $model_message->from_customer_id = $customer_id;
                        $model_message->to_customer_id = $customer['customer_id'];
                        $model_message->content = serialize($message_content);
                        $model_message->is_read = 0;
                        $model_message->post_time = date("Y-m-d H:i:s");
                        $model_message->is_del = 0;
                        $model_message->need_verify=1;
                        $model_message->is_system = 1;
                        $model_message->save();
                    }else{
                        $message = "您的好友邀请您加入圈子".$title."。快来注册吧！";
                        Helper::Sendsmqxt($telephone,$message);
                    }
                }
            }

            $msg['msg'] = "success";
            $msg['group_id'] = $group_id;
            return $msg;
        }else{
            $msg['msg'] = "error";
            return $msg;
        }
    }
    /*
     * 获取某圈子内活动；
     * */
    public function actionGetgroupevents(){
        $group_id = \Yii::$app->request->post('cid'); //圈子id
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);
        $data = [];
        $data['where'] = ['by_group_id'=>$group_id];
        $data['orderby'] = 'created_at DESC';
        $data['offset'] = isset($count[0]) ? $count[0] : 0;
        $data['limit'] = isset($count[1]) ? $count[1] : 20;

        $model = ClubEvents::find();
        $results = $this->actionFormatdata($model,$data);

        foreach($results as $k => $v){
            $results[$k]['customer'] = Helper::getCustomerformat($v['by_customer_id']);
        }
        return $results;

    }
    public function actionJoingroup(){
        $customer_id = \Yii::$app->request->post("customer_id");
        $group_id = \Yii::$app->request->post("cid");

        $modelgroup = new ClubGroupMember();
        $modelgroup->customer_id = $customer_id;
        $modelgroup->group_id = $group_id;
        $modelgroup->status = '1';
        $modelgroup->created_at = date("Y-m-d H:i:s");

       if( $modelgroup->save()){
           $data=[
               'type_name_id' => 3,
               'item_id'       => $group_id,
               'action'        => '加入圈子',
               'customer_id'     => $customer_id, //

           ];
           //Helper::setMyPage($customer_id,$data); //圈主同意时候执行
           $group = ClubGroup::findOne($group_id);
           $msgdata = [
               'from_group_id' => $group->group_id,
               'from_events_id' => '',
               'need_verify' =>  1,
               'is_system'  => 1,
               'content'   => array(
                   'type_name_id' => 3,
                   'item_id'   => $group_id,
                   'content'   => $group->title,
                   'do'        => "申请加入"
               ),
           ];
            Helper::setMessage($customer_id,$group->customer_id,$msgdata);
           /*$myfriends = ClubRelation::find()->where(['customer_id'=> $customer_id ])->all();
          foreach($myfriends as $friend){
               Helper::setTrends($friend->friend_customer_id,$data); //告知我的朋友们，我加入了某个圈子
          }*/
           return "success";
       }else{
           return "false";
       }

    }
    public function actionGetmycreategroup(){
        $customer_id = \Yii::$app->request->post("customer_id");
        //$customer_id = 314;
        $count = \Yii::$app->request->post("count");
        if(is_null($count)){
            $start = 0;
            $limit = 10;
        }else{
            $count = explode('-',$count);
            $start = isset($count[0]) ? $count[0] : 0;
            $limit = isset($count[1]) ? $count[1] : 20;
        }

        $data['where'] = ["customer_id"=>$customer_id];
        $data['orderby'] = 'created_at DESC';
        $data['offset'] = $start;
        $data['limit'] =  $limit;

        $model = ClubGroup::find();
        $mygroups = $this->actionFormatdata($model,$data);
        $results = array();
        if(!empty($mygroups)){
            foreach($mygroups as $key => $group_info){

                $cover_image = Image::resize( $group_info['logo'],100,100);
                $member_num = ClubGroupMember::find()->where(["group_id"=>$group_info['group_id'],"status"=>1])->count(); //成员数量
               // $experience_num = ClubExperience::find()->where(["event_id" => $group_info->group_id])->count(); //体验数量
                $results[] = array(
                    'group_id' => $group_info['group_id'],
                    'title'     => isset($group_info['title']) ? $group_info['title'] : "",
                    'description'     => isset($group_info['description']) ? $group_info['description'] : "",
                    'cover_image'=> $cover_image,
                    'member_num' => $member_num,
                   // 'exp_num'   => $experience_num
                );
            }
        }
        return $results;
    }
    public function actionGetmyjoingroup(){
        $customer_id = \Yii::$app->request->post("customer_id");
         //$customer_id = 314;
        $count = \Yii::$app->request->post("count");
        if(is_null($count)){
            $start = 0;
            $limit = 10;
        }else{
            $count = explode('-',$count);
            $start = isset($count[0]) ? $count[0] : 0;
            $limit = isset($count[1]) ? $count[1] : 20;
        }
        $data['where'] = ["customer_id"=>$customer_id];
        $data['orderby'] = 'created_at DESC';


        //$events = ClubEvents::find()->joinWith('members')->where([ClubEventsMember::tableName().'.customer_id'=>$customer_id,'by_customer_id'=>$customer_id])->groupBy("events_id")->asArray()->offset($data['offset'])->limit($data['limit'])->all();
        $sql = "SELECT `jr_club_group`.* FROM `jr_club_group` LEFT JOIN `jr_club_group_member` ON `jr_club_group`.`group_id` = `jr_club_group_member`.`group_id` WHERE `jr_club_group_member`.customer_id=".$customer_id." AND jr_club_group.customer_id <>".$customer_id." GROUP BY `group_id`  limit ".$start." ,".$limit;
        $command = \Yii::$app->db->createCommand($sql);
        $groups = $command->queryAll();

        $results = array();
        if(!empty($groups)){
            foreach($groups as $group_info){

                $cover_image = Image::resize( $group_info['logo'],100,100);
                $member_num = ClubGroupMember::find()->where(["group_id"=>$group_info['group_id']])->count(); //成员数量
               // $experience_num = ClubExperience::find()->where(["group_id" => $group_info['group_id']])->count(); //体验数量
                if($customer_id != $group_info['customer_id']){
                    $results[] = array(
                        'group_id' => $group_info['group_id'],
                        'title'     => isset($group_info['title']) ? $group_info['title'] : "",
                        'description'     => isset($group_info['description']) ? $group_info['description'] : "",
                        'cover_image'=> $cover_image,
                        'member_num' => $member_num,
                       // 'exp_num'   => $experience_num
                    );
                }
            }
        }
        return $results;
    }
    /*
   * 格式化数据；
   * */
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
    public function actionGetgroupmember(){
        $group_id = \Yii::$app->request->post("cid");

        $group_members = ClubGroupMember::find()->where(['group_id'=>$group_id,'status'=>1])->all();
        if(!empty($group_members)){
            foreach($group_members as $customer){
                $result[] = Helper::getCustomerformat($customer->customer_id);
            }
            return $result;
        }else{
            return "";
        }


    }
}
