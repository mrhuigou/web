<?php

namespace api\controllers\club\v1;

use api\models\V1\City;
use api\models\V1\ClubComment;
use api\models\V1\ClubEvents;
use api\models\V1\ClubEventsFee;
use api\models\V1\ClubEventsFeeLog;
use api\models\V1\ClubEventsMember;
use api\models\V1\ClubExperience;
use api\models\V1\ClubGroup;
use api\models\V1\ClubGroupMember;
use api\models\V1\Customer;
use api\models\V1\CustomerLevel;
use api\models\V1\District;
use api\models\V1\Zone;
use common\component\Helper\Helper;
use common\component\image\Image;



class MyzoneController extends \yii\rest\Controller
{
    public function actionIndex()
    {

        $image = Image::resize("noimage.jpg",100,100);

        $data['type_name_id'] = 1;


    }
    public function actionGetmypage(){
        $customer_id = \Yii::$app->request->post("customer_id");
        // $customer_id = 41621;
        //获取A用户的动态key
        $trendsinbox = \Yii::$app->redis->zrevrange("MyPageOutbox_".$customer_id,0,-1);
        //print_r($trendsinbox);exit;// Array ( [0] => Trends:exp_41 )
        if(!empty($trendsinbox)){
            $list=array();
            foreach($trendsinbox as $trendskey){
                $key = \Yii::$app->redis->hget($trendskey,'key'); // trendskey like Customer:314  Exp:41  Group:1  Events:1
                //print_r($key);exit;
                if(!empty($key)){
                    $c_id = \Yii::$app->redis->hget($trendskey,'customer_id');
                    $action = \Yii::$app->redis->hget($trendskey,'action');

                    $what = Helper::getKeyValue($key);
                   // $who = Helper::getKeyValue("Customer:".$c_id);
                    //if(empty($who)){
                    $who = Helper::getCustomerformat($c_id);
                   // }
                    if(!empty($what)){
                        if(isset($what['type_name_id']) && !empty($what['type_name_id'])){
                            $what['num_comment'] = ClubComment::find()->where(["type_name_id"=>$what['type_name_id'],"content_id"=>$what['item_id']])->count();
                            if($what['type_name_id'] == 3){
                                $what['num_events'] = strval(ClubEvents::find()->where(['by_group_id'=>$what['item_id']])->count());
                                $what['num_member'] = strval(ClubGroupMember::find()->where(['group_id'=>$what['item_id']])->count());
                                if(isset($what['logo'])){
                                    $what['logo']   = Image::resize($what['logo'],0,0);
                                }

                            }elseif($what['type_name_id'] == 4){

                                $event = ClubEvents::findOne($what['item_id']);
                               // print_r($event);exit;
                                $what['start_time'] = $event->start_time;
                                $event_member = ClubEventsMember::find()->where(['events_id'=>$what['item_id'],'status'=>1])->asArray()->all();
                                $what['num_member'] = strval(count($event_member));
                                if(!empty($event_member)){
                                    foreach($event_member as $member){
                                        if($c_id == $member['customer_id']){
                                            $what['join_time'] = $member['created_at'];
                                        }
                                    }
                                }
                                if($event->has_fee){
                                    $total = 0;
                                    $event_fee = ClubEventsFee::find()->where(['events_id'=>$event->events_id])->all();
                                    //print_r($event_fee);exit;
                                    foreach($event_fee as $fee){
                                        $total = $total + $fee['price'];
                                    }
                                    $what['fee_total'] = strval($total);
                                }else{
                                    $what['fee_total'] = "0";
                                }



                            }elseif($what['type_name_id'] == 1) {
                                $img_array = array();
                                $exp = ClubExperience::find()->where(['exp_id'=> $what['item_id']])->one();
                                if(!empty($exp) && !empty($exp['image_array'])){
                                    $image_array = unserialize($exp['image_array']);
                                    if(!empty($image_array)){
                                        foreach($image_array as $key => $image){
                                            if($key < 3){
                                                $img_array[] = Image::resize($image['image'],0,0);
                                            }
                                        }
                                    }
                                }else{
                                    $img_array = [];
                                }
                                $what['image_array'] = $img_array;


                            }
                            if(isset($what['cover_image'])){
                                $what['cover_image']   = Image::resize($what['cover_image'],0,0);
                            }

                        }

                    }

                    if(!empty($what) && !empty($who) && !empty($action)){
                        $list[] = array(
                            'who' => $who,
                            'action' => $action,
                            'what'  => $what,
                        );
                    }

                }
            }
            return $list;
        }else{
            return array();
        }
    }
    public function actionGetmytransaction(){
        $customer_id = \Yii::$app->request->post("customer_id");
        //$customer_id = 41202;
        $event_members = ClubEventsMember::find()->where(["customer_id"=>$customer_id])->groupBy("events_id")->all();
       if(!empty($event_members)){
           $whereor = '';
           foreach($event_members as $event_id){
               $orarray[] = $event_id->events_id;
           }
           // $whereor = trim($whereor,",");
           // $orarray = explode(",",$whereor);
           $events = ClubEvents::find()->where(["events_id" => $orarray])->orderBy("created_at")->all();
           $return_data = array();
           // $outlays = 0;
           $income = 0;
           foreach($events as $event){
               $event_total_member = "0";
               $event_total_member = ClubEventsFeeLog::find()->where(["events_id"=>$event->events_id])->count();
               if($event->by_customer_id == $customer_id ){
                   $command = \Yii::$app->db->createCommand("SELECT SUM(price) AS total FROM jr_club_events_fee_log WHERE events_id=".$event->events_id);
                   $events_fee_total = $command->queryOne();
                   $income = $income + $events_fee_total['total'];
                   if(empty($events_fee_total['total'])){
                       $events_fee_total['total'] = 0;
                   }

                   $return_data['in'][] = array(
                       'events_title' => $event->title,
                       'action'            => "发起活动",
                       'create_time'    => $event->created_at,
                       'fee_total'     => $events_fee_total['total'],
                       'member_total'  => strval($event_total_member),
                   );
               }else{
                   $command = \Yii::$app->db->createCommand("SELECT SUM(price) AS total FROM jr_club_events_fee_log WHERE events_id=".$event->events_id ." AND customer_id=".$customer_id);
                   $events_fee_total = $command->queryOne();
                   if(empty($events_fee_total['total'])){
                       $events_fee_total['total'] = 0;
                   }
                   $log = ClubEventsFeeLog::find()->where(["customer_id"=> $customer_id,"events_id"=>$event->events_id])->one();
                   $return_data['out'][] = array(
                       'events_title' => $event->title,
                       'action'            => "参加活动",
                       'create_time'    => $event->created_at,
                       'fee_total'     => $events_fee_total['total'],
                       'member_total'  =>  strval($event_total_member),
                   );
               }
           }
           // $total = $income-$outlays;
           $return_data['totals'] = $income;
           return $return_data;
       }else{
           return array();
       }

    }
    public function actionGetmyaccount(){

        $customer_id = \Yii::$app->request->post("customer_id");

        //$customer_id = 314;
        $customer_info = Customer::findOne($customer_id);
        if(!empty($customer_info)){
            if(isset($customer_info->customer_level_id) && !empty($customer_info->customer_level_id)){
                $level_id = $customer_info->customer_level_id;
            }else{
                $level_id = 1;
            }
            if(is_null($customer_info->idcard_validate)){
                $idcardauth = 0;
            }else{
                $idcardauth = $customer_info->idcard_validate;
            }
            if(is_null($customer_info->authen_business)){
                $businessauth = 0;
            }else{
                $businessauth = $customer_info->authen_business;
            }
            $customer_level = CustomerLevel::findOne($level_id);

            $command = \Yii::$app->db->createCommand("SELECT SUM(points) AS total FROM jr_club_customer_energy WHERE customer_id=".$customer_id);
            $customer_energy = $command->queryOne();
            $command = \Yii::$app->db->createCommand("SELECT SUM(points) AS total FROM jr_club_customer_shell WHERE customer_id=".$customer_id);
            $customer_shell = $command->queryOne();

            //$command = \Yii::$app->db->createCommand("SELECT SUM(amount) AS total FROM jr_customer_transaction WHERE customer_id=".$customer_id);
            // $customer_transaction  = $command->queryOne();
            $events = ClubEvents::find()->where(['by_customer_id'=>$customer_id])->all();
            $fee = 0;
            if(!empty($events)){
                foreach($events as $event){
                    $command = \Yii::$app->db->createCommand("SELECT SUM(price) AS total FROM jr_club_events_fee_log WHERE events_id=".$event->events_id);
                    $fee_total = $command->queryOne();
                    $fee = $fee + $fee_total['total'];
                }
            }
            if(empty($customer_energy['total'])){
                $customer_energy['total'] = 0;
            }
            if(empty($customer_shell['total'])){
                $customer_shell['total'] = 0;
            }
            $result = [
                'nickname' => $customer_info->nickname,
                'firstname' => $customer_info->firstname,
                'level'     => $customer_level->name,
                'idcardauth'=> $idcardauth ,
                'businessauth' => $businessauth,
                'energy'        => $customer_energy['total'],
                'shell'         => $customer_shell['total'],
                'fee_total'   => $fee,
            ];
            return $result;
        }else{
            return "false";
        }


    }
    /*
     * ��ȡ���ҵ����顱
     * */
    public function actionGetmyexperience(){
        $customer_id = \Yii::$app->request->post("customer_id");
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);
        $model = ClubExperience::find();
        $data = [];
        $data['where'] = ["customer_id"=>$customer_id];
        $data['orderby'] = 'create_time DESC';
        $data['offset'] = isset($count[0]) ? $count[0] : 0;
        $data['limit'] = isset($count[1]) ? $count[1] : 20;
        $results = $this->actionFormatdata($model,$data);
        if(!empty($results)){
            foreach($results as $key => $exp){
                $results[$key]['customer'] = Helper::getCustomerformat($exp['customer_id']);
            }
        }
        return $results;
    }
    /*
    * ��ȡ���ҵĻ��
    * */
    public function actionGetmyevent(){
        $customer_id = \Yii::$app->request->post("customer_id");

        $count = \Yii::$app->request->post("count");

        $data['where'] = ["customer_id"=>$customer_id,"status"=> 1];
        $data['orderby'] = 'created_at DESC';
        $data['offset'] = isset($count[0]) ? $count[0] : 0;
        $data['limit'] = isset($count[1]) ? $count[1] : 20;
        $model = ClubEventsMember::find();
        $eventmember = $this->actionFormatdata($model,$data);
        $results = array();
        if(!empty($eventmember)){
            foreach($eventmember as $event){
                $event_info = ClubEvents::findOne($event['events_id']);

                $cover_image = Image::resize( $event_info->cover_image,100,100);
                $member_num = ClubEventsMember::find()->where(["events_id"=>$event_info->events_id])->count(); //��Ա����
                $experience_num = ClubExperience::find()->where(["event_id" => $event_info->events_id])->count(); //��������
                if($customer_id == $event_info->by_customer_id){
                    $results['in'][] = array(
                        'events_id' => $event_info->events_id,
                        'title'     => $event_info->title,
                        'cover_image'=> $cover_image,
                        'member_num' => $member_num,
                        'exp_num'   => $experience_num
                    );
                }else{
                    $results['out'][] = array(
                        'events_id' => $event_info->events_id,
                        'title'     => $event_info->title,
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
   * ��ȡ���ҵ�Ȧ�ӡ�
   * */
    public function actionGetmygroup(){
        $customer_id = \Yii::$app->request->post("customer_id");
        //$customer_id = 41536;
        $count = \Yii::$app->request->post("count");

        $data['where'] = ["customer_id"=>$customer_id];
        $data['orderby'] = 'created_at DESC';
        $data['offset'] = isset($count[0]) ? $count[0] : 0;
        $data['limit'] = isset($count[1]) ? $count[1] : 20;

        $model = ClubGroupMember::find();
        $groupmember = $this->actionFormatdata($model,$data);
        $results = array();

        if(!empty($groupmember)){
            foreach($groupmember as $group){
                if(!empty($group['group_id'])){
                    $group_info = ClubGroup::findOne($group['group_id']);

                    $cover_image = Image::resize( $group_info->logo,100,100);
                    $member_num = ClubGroupMember::find()->where(["group_id"=>$group_info->group_id])->count(); //��Ա����
                    $event_num = ClubEvents::find()->where(["by_group_id"=>$group_info->group_id])->count();
                    if($group_info->customer_id == $customer_id){
                        $results['in'][] = array(
                            'group_id' => $group_info->group_id,
                            'title'     => $group_info->title,
                            'cover_image'=> $cover_image,
                            'member_num' => $member_num,
                            'event_num'  => $event_num,

                        );
                    }else{
                        $results['out'][] = array(
                            'group_id' => $group_info->group_id,
                            'title'     => $group_info->title,
                            'cover_image'=> $cover_image,
                            'member_num' => $member_num,
                            'event_num'  => $event_num,

                        );
                    }
                }


            }
        }

        return $results;
    }

    /*
    * ��ʽ����ݣ�
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

    /*
     * */
    public function actionGetmyinfo(){
        /**/
        $customer_id = \Yii::$app->request->post("customer_id");
       // $customer_id = 314;
        if(!empty($customer_id)){
            $customer = Customer::findOne($customer_id);
            if(!empty($customer->city_id)){
                $city_info = City::findOne($customer->city_id);
                $city_name = $city_info->name;
            }else{
                $city_name = "保密";
            }
            if(!empty($customer->zone_id)){
                $zone_info = Zone::findOne($customer->zone_id);
                $zone_name = $zone_info->name;
            }else{
                $zone_name = "保密";
            }
            if(!empty($customer->district_id)){
                $district_info = District::findOne($customer->district_id);
                $district_name = $district_info->name;
            }else{
                $district_name = "保密";
            }

            $result = array(
                "customer_id"   => $customer->customer_id,
                "firstname"     =>isset($customer->firstname) ? $customer->firstname : "",
                "nickname"      => isset($customer->nickname) ? $customer->nickname : "",
                "birthday"      => isset($customer->birthday) ? $customer->birthday : "",
                "province"      => $zone_name,
                "city"          => $city_name,
                "district"      => $district_name,
                "email"         =>  isset($customer->email) ? $customer->email : "",
                "telephone"     => isset($customer->telephone) ? $customer->telephone : "",
                "gender"        => isset($customer->gender) ? $customer->gender : "女",
                "photo"         => Image::resize($customer->photo,100,100)

            );
            return $result;
        }else{
            return "false";
        }


    }
    public function actionEditmyinfo(){
        $customer_id = \Yii::$app->request->post("customer_id");
        $filed = \Yii::$app->request->post("f");
        if(!empty($filed)){
            $value = \Yii::$app->request->post("nf");
            $customer = Customer::findOne($customer_id);

            if(strtolower($filed) == "nickname"){
                $customer->$filed = $value;
                \Yii::$app->redis->Hset("Customer:".$customer_id,'nickname',$value);
            }elseif(strtolower($filed) == "gender"){
                $customer->$filed = $value;
                \Yii::$app->redis->Hset("Customer:".$customer_id,'gender',$value);
            }elseif(strtolower($filed) == "birthday"){
                $value = date("Y-m-d",strtotime($value));

                $customer->$filed = $value;
                $age = date("Y") - date("Y",strtotime($value));
                \Yii::$app->redis->Hset("Customer:".$customer_id,'birthday',$value);
                \Yii::$app->redis->Hset("Customer:".$customer_id,'age',strval($age));
            }elseif(strtolower($filed) == "photo"){
                $customer->$filed = $value;
                $photo = Image::resize($value,100,100);
                \Yii::$app->redis->Hset("Customer:".$customer_id,'photo',$photo);
            }
            if($customer->save()){
                return $customer->$filed;
            }else{
                return "false";
            }
        }


    }
    public function getAllarea(){

    }

}
