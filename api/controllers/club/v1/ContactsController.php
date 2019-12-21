<?php

namespace api\controllers\club\v1;

use api\models\V1\City;
use api\models\V1\ClubEvents;
use api\models\V1\ClubGroup;
use api\models\V1\ClubGroupMember;
use api\models\V1\ClubMessage;
use api\models\V1\ClubRelation;
use api\models\V1\Customer;
use api\models\V1\District;
use api\models\V1\Zone;
use api\modules\oauth2\filters\auth\CompositeAuth;
use common\component\Helper\Helper;
use common\component\image\Image;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;


class ContactsController extends \yii\rest\Controller
{
    //通讯录 Controller
    public function actionIndex()
    {
        return $this->render('index');
    }
    //获取某用户的好友列表
    /**/
    public function actionGetfriendlist(){

        $customer_id = \Yii::$app->request->post("customer_id");
		//$customer_id = 41621;
        $model = ClubRelation::find();
        $data = [];
        $data['where'] = ['customer_id'=>$customer_id,'status'=>1];

        $result = $this->actionFormatdata($model,$data);
        $result_data = array();
        if(!empty($result)){
            $count = 0;
            foreach($result as $key => $frind_id){
                $frindinfo = Customer::findOne($frind_id['friend_customer_id']);
                if(!empty($frindinfo->nickname)){
                    $initial = Helper::getFirstchar($frindinfo->nickname);
                }else{
                    $initial = "*";
                }
                if(!isset($result_data[$initial])){
                    $result_data[$initial] = [];
                }
                if(!empty($frindinfo)){
                    $age = date("Y") - date("Y",strtotime($frindinfo->birthday));
                    $photo = Image::resize($frindinfo->photo,100,100);
                    if(!empty($frindinfo->gender)){
                        $gender = $frindinfo->gender;
                    }else{
                        $gender = '女';
                    }
                    if(!empty($frindinfo->zone_id)){
                        $zone = Zone::findOne($frindinfo->zone_id);
                        if(!empty($zone)){
                            $zone_name = $zone->name;
                        }else{
                            $zone_name = "";
                        }
                    }else{
                        $zone_name = "";
                    }
                    if(!empty($frindinfo->city_id)){
                        $city = City::findOne($frindinfo->city_id);
                        if(!empty($city)){
                            $city_name = $city->name;
                        }else{
                            $city_name = "";
                        }
                    }else{
                        $city_name = "";
                    }
                    if(!empty($frindinfo->district_id)){
                        $district = District::findOne($frindinfo->district_id);
                        if(!empty($district)){
                            $district_name = $district->name;
                        }else{
                            $district_name = "";
                        }
                    }else{
                        $district_name = "";
                    }
                    $count = count($result_data[$initial]);

                    $result_data[$initial][$count]['nickname'] = $frindinfo->nickname;
                    $result_data[$initial][$count]['firstname'] = $frindinfo->firstname;
                    $result_data[$initial][$count]['customer_id'] = $frindinfo->customer_id;
                    //$result_data[$key]['zone_id'] = $frindinfo->zone_id;
                    $result_data[$initial][$count]['zone_name'] = $zone_name;
                   // $result_data[$key]['city_id'] = $frindinfo->city_id;
                    $result_data[$initial][$count]['city_name'] = $city_name;
                   // $result_data[$key]['zone_id'] = $frindinfo->zone_id;
                    $result_data[$initial][$count]['zone_name'] = $zone_name;
                    //$result_data[$key]['district_id'] = $frindinfo->district_id;
                    $result_data[$initial][$count]['district_name'] = $district_name;
                    $result_data[$initial][$count]['age']  = $age;
                    $result_data[$initial][$count]['gender'] = $gender;
                    $result_data[$initial][$count]['photo'] = $photo;
                   // $count++;
                }
            }
        }
       ksort($result_data);
        if(!empty($result_data)){
            $results = [];
            foreach($result_data as $key => $v){
                $temp['index'] = $key;
                $temp['customer'] = $v;
                $results[] = $temp;
            }
            return $results;
        }else{
            return array();
        }

    }
    //获取某用户的圈子列表
    /**/
    public function actionGetgrouplist(){
        $customer_id = \Yii::$app->request->post("customer_id");
        //$customer_id = 41621;
        $orderby = \Yii::$app->request->post("orderby");
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);

        $data = [];
        if(!empty($customer_id)){
            $data['where'] = ['customer_id'=>$customer_id,'status'=>1];
        }
        $data['orderby'] = 'created_at DESC';

        $data['offset'] = isset($count[0]) ? $count[0] : 0;
        $data['limit'] = isset($count[1]) ? $count[1] : 10;


        $model = ClubGroupMember::find();

        $result = $this->actionFormatdata($model,$data);
        $result_data = array();
        if(!empty($result)){
            $count = 0;
            foreach($result as $key => $groupnumber){
                $group = ClubGroup::findOne($groupnumber['group_id']);
                if(!empty($group->title)){
                    $initial = Helper::getFirstchar($group->title);
                }else{
                    $initial = "*";
                }
                if(!empty($group)){

                    $cover_image = Image::resize( $group->logo,100,100);
                    $member_num = ClubGroupMember::find()->where(["group_id"=>$group->group_id])->count(); //成员数量
                    $event_num = ClubEvents::find()->where(["by_group_id"=>$group->group_id])->count();


                    if(!isset($result_data[$initial])){
                        $result_data[$initial] = [];
                    }
                    $count = count($result_data[$initial]);
                    $result_data[$initial][$count]['group_id'] = $group->group_id;
                    $result_data[$initial][$count]['title'] = $group->title;
                    $result_data[$initial][$count]['logo']  = $cover_image;
                    $result_data[$initial][$count]['description']  = $group->description;
                    $result_data[$initial][$count]['cover_image']  = $cover_image;
                    $result_data[$initial][$count]['member_num']  = $member_num;
                    $result_data[$initial][$count]['event_num']  = $event_num;
                }
            }
        }
        ksort($result_data);
        if(!empty($result_data)){
            $results = [];
            foreach($result_data as $key => $v){
                $temp['index'] = $key;
                $temp['customer'] = $v;
                $results[] = $temp;
            }
            return $results;
        }else{
            return array();
        }


    }




    //格式化数据
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
        $result = $model->asArray()->all();

        return  $result;
    }
    public function actionAgreefriend($customer_id,$friend_id){

        $relation = ClubRelation::find()->where(["customer_id"=>$customer_id,"friend_customer_id"=>$friend_id])->one();
        if(!empty($relation)){
            $relation->status = 1;
            $relation->save();
        }else{
            $model = new ClubRelation();
            $model->customer_id = $customer_id;
            $model->friend_customer_id = $friend_id;
            $model->status = 1;
            $model->created_at = date("Y-m-d H:i:s");
            $model->save();
        }
        $data = [];
        $data['type_name_id'] = 18;
        $data['item_id'] = $friend_id;
        $data['action'] = "成为好友";
        $data['customer_id'] = $customer_id;

        Helper::setMyPage($customer_id,$data);

        $eachrelation = ClubRelation::find()->where(["customer_id"=>$friend_id,"friend_customer_id"=>$customer_id])->one();
        if(!empty($eachrelation)){
            $eachrelation->status = 1;
            $eachrelation->save();
        }else{
            $eachfriends = new ClubRelation();
            $eachfriends->customer_id = $friend_id;
            $eachfriends->friend_customer_id = $customer_id;
            $eachfriends->status = 1;
            $eachfriends->created_at = date("Y-m-d H:i:s");
            $eachfriends->save();
        }

        $data = [];
        $data['type_name_id'] = 18;
        $data['item_id'] = $customer_id;
        $data['action'] = "成为好友";
        $data['customer_id'] = $friend_id;

        Helper::setMyPage($friend_id,$data);
        return true;
    }
    /*
     *发送添加好友请求
     **/
    public function actionAddfriend(){
        $customer_id = \Yii::$app->request->post("customer_id");
        $friend_id = \Yii::$app->request->post("fid");

        $model = new ClubRelation();
        $model->customer_id = $customer_id;
        $model->friend_customer_id = $friend_id;
        $model->status = 0;
        $model->created_at = date("Y-m-d H:i:s");
       // $connection = \Yii::$app->db;
        //$connection->open();
        //$transaction = $connection->beginTransaction();
        //$result = [];
        //try {
            $model->save();

            $msgdata = [
                'from_group_id' => '',
                'from_events_id' => '',
                'need_verify' =>  1,
                'is_system'  => 1,
                'content'   => array(
                    'type_name_id' => 18,
                    'item_id'   => $model->relation_id,
                    'content'   => '',
                    'do'        => "请求加你好友"
                ),
            ];
            Helper::setMessage($customer_id,$friend_id,$msgdata);

            $msg['msg'] = "success";
            return $msg;

      //  }catch ( Exception $e) {
        //    $transaction->rollBack();
       //     $result['msg'] = "error";
       //     return $result;

        //}

    }

    //邀请通讯录好友 注册每日惠购生活圈
    public  function actionInvitecontactfriend(){
        $phone = \Yii::$app->request->post("phoneno");
        $customer_id = \Yii::$app->request->post("customer_id");
        $customer = Customer::findOne($customer_id);
        if(preg_match("/13\d{9}|15\d{9}|18\d{9}|17\d{9}/",$phone)){
            $msg = "您的好友".$customer->nickname."(".$customer->firstname.") 邀请您加入每日惠购生活圈";
            if(Helper::Sendsmqxt($phone,$msg) == 'success'){
                $result['msg'] = "success";
            }else{
                $result['msg'] = "error";
            }
        }else{
            $result['msg'] = "error";
        }
        return $result;

    }
    /*
    *获取通讯录商城用户列表
     * */
    public function actionSendcontact(){
        $customer_id = \Yii::$app->request->post("customer_id");
        $contact_string = \Yii::$app->request->post("contact");
        $contact_string = trim($contact_string,",");
        $contact_array = explode(",",$contact_string);
        $result_data = array();
        if(!empty($contact_array)){
            foreach($contact_array as $key=> $telephone){
                $is_friend = 0;
                $is_customer = 0;
                $is_self = 0;
                $friend_customer_id = 0;
                $customer = Customer::find()->where(["telephone"=>$telephone,"status"=>1])->one();
                $nickname = "";
                $photo = Image::resize("noimage.jpg",100,100);
                if(!empty($customer) ){
                    if($customer->telephone == $telephone){
                        $is_self = 1;
                    }
                    $is_customer = 1;
                    $friend_customer_id = $customer->customer_id;
                    $nickname = $customer->nickname;
                    $photo = Image::resize($customer->photo,100,100);
                    $relation = ClubRelation::find()->where(["customer_id"=>$customer_id,"friend_customer_id"=>$customer->customer_id])->one();
                    if(!empty($relation)){
                        $is_friend = 1;
                    }
                }

                $result_data[$key]["is_friend"] = $is_friend;
                $result_data[$key]["is_customer"] = $is_customer;
                $result_data[$key]["telephone"] = $telephone;
                $result_data[$key]["is_self"] = $is_self;
                $result_data[$key]["customer_id"] = $friend_customer_id;
                $result_data[$key]["nickname"] = $nickname;
                $result_data[$key]["photo"] = $photo;


            }
        }

        return $result_data;
    }
    public function bindActionParams($action, $params){
        return $params;
/*
        if($action->getUniqueId() == 'club/v1/contacts/agreefriend'){
            if(self::actionAgreefriend($params['customer_id'],$params['to_customer_id'])){
                return true;
            }
        }else{
            return $params;
        }
*/
    }
    /*
     *获取某用户的最近聊天记录
     *
     */
    public function actionGetsipcdr(){
        $customer_id = \Yii::$app->request->post("customer_id");
        //$customer_id = 41621;
        $sql = "select * FROM (
        select calldate,src,dst,case when src='".(int)$customer_id."' then 'out' when src <> '".(int)$customer_id."' then 'in' end flag,case when dstchannel='' then 'noanswered'
        when dstchannel <> '' then 'answered' end answeredflg,duration from jr_sip_cdr where src='".(int)$customer_id."' or `dst`='".(int)$customer_id."'
        order by calldate desc) AS cdr GROUP BY cdr.src ,cdr.dst ORDER BY cdr.calldate DESC";
        $command = \Yii::$app->db->createCommand($sql);
        $cdrs = $command->queryAll();
        foreach ($cdrs as $key => $cdr) {
            if(isset($cdr['src']) && !empty($cdr['src'])){
                $cdrs[$key]['src'] = Helper::getCustomerformat($cdr['src']);
            }
            if(isset($cdr['dst']) && !empty($cdr['dst'])){
                $cdrs[$key]['dst'] = Helper::getCustomerformat($cdr['dst']);
            }
        }

        return $cdrs;

    }
}
