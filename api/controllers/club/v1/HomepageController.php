<?php

namespace api\controllers\club\v1;

use api\models\V1\ClubComment;
use api\models\V1\ClubEvents;
use api\models\V1\ClubEventsFee;
use api\models\V1\ClubEventsMember;
use api\models\V1\ClubExperience;
use api\models\V1\ClubGroupMember;
use common\component\Helper\Helper;
use common\component\image\Image;

class HomePageController extends \yii\rest\Controller
{
    public function actionIndex()
    {
       // Image::uploadimage("x");
        exit;
        return $this->render('index');
    }
    /*
   * 获取 体验列表
   * */
    public function actionGethpnewtrends(){

        $model = ClubExperience::find();
        $data =[];
        $data['orderby'] = 'create_time desc';
        $data['where'] = ['is_del' => 0];
        $list = $this->actionGetlist($model,$data);
        if(!empty($list)){
            $return = array();
            foreach($list as $key => $exp){
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
                $return[$key]['customer'] = Helper::getCustomerformat($exp['customer_id']);
                $return[$key]['cover_image'] = Image::resize($exp['cover_image'],0,0);
                $return[$key]['image_array'] = $images;
                $return[$key]['num_comment'] = ClubComment::find()->where(["type_name_id"=>1,"content_id"=>$exp['exp_id']])->count();
                $return[$key]['customer_id'] = strval($exp['customer_id']);
                $return[$key]['exp_id'] = strval($exp['exp_id']);
                $return[$key]['content'] = $exp['content'];
                $return[$key]['create_time'] = $exp['create_time'];
                $return[$key]['last_update_time'] = $exp['last_update_time'];


            }
        }
        return  $return;
    }
/*
 * 首页 获取最新活动
 * */
    public function actionGethpnewevents(){
        $model = ClubEvents::find();
        $data =[];
        $data['orderby'] = 'created_at desc';

        $list = $this->actionGetlist($model,$data);
        if(!empty($list)){
            foreach($list as $key => $event){
                $images = array();
                if(isset($event['image_array']) && !empty($event['image_array'])){
                    $image_array = unserialize($event['image_array']);
                    if(is_array($image_array)){
                        foreach($image_array as $k => $img){
                            $images[$k]['image'] =  Image::resize($img['image'],0,0);
                            //$images[$k]['des'] = Image::resize($img['des'],0,0);
                        }
                    }else{
                        $images = array();
                    }
                }
                if($event['has_fee']){
                    $total = 0;
                    $event_fee = ClubEventsFee::find()->where(['events_id'=>$event['events_id']])->all();
                    //print_r($event_fee);exit;
                    foreach($event_fee as $fee){
                        $total = $total + $fee['price'];
                    }
                    $list[$key]['fee_total'] = strval($total);
                }else{
                    $list[$key]['fee_total'] = "0";
                }
                $list[$key]['image_array'] = $images;
                $list[$key]['cover_image'] = Image::resize($event['cover_image'],0,0);
                $list[$key]['customer'] = Helper::getCustomerformat($event['by_customer_id']);
                $list[$key]['num_comment'] = ClubComment::find()->where(["type_name_id"=>4,"content_id"=>$event['events_id']])->count();
                $list[$key]['assemble_address'] = isset($event['assemble_address']) ? $event['assemble_address'] : "";
                $list[$key]['assemble_time'] = isset($event['assemble_time']) ? $event['assemble_time'] : "";

                $count_member = ClubEventsMember::find()->where(['events_id'=>$event['events_id'],'status'=>1])->count();
                $list[$key]['count_member'] = strval($count_member);
            }
        }
        return $list;
    }

    /*
     * 获取附近的人
     * */
    public function  actionGethpnearby(){
        $range = \Yii::$app->request->post("r");
        $location = \Yii::$app->request->post("l");

        $location_array = explode(",",$location);
        $lat = $location_array[0];
        $long = $location_array[1];

        $range = (int)$range;
        if($range <= 0){
            $range = 500;
        }

        $command = \Yii::$app->db->createCommand("SELECT customer_id,nickname,email,telephone,photo,latitude,longitude, GETDISTANCE(latitude,longitude,".$lat.",".$long.") AS distance FROM jr_customer HAVING distance <".$range." ORDER BY distance");
        $customers = $command->queryAll();

        foreach($customers as $key => $customer){
            $customers[$key]['photo'] = Image::resize($customer['photo'],100,100);
            if(!isset($customer['nickname']) ||empty($customer['nickname'])){
                $customer[$key]['nickname'] = "匿名";
            }
        }

        $command = \Yii::$app->db->createCommand("SELECT events_id,events_type,cover_image,title,latitude,longitude ,GETDISTANCE(latitude,longitude,".$lat.",".$long.") AS distance FROM jr_club_events HAVING distance < ".$range." ORDER BY distance");
        $events = $command->queryAll();
        foreach($events as $key => $event){
            $events[$key]['cover_image'] = Image::resize($event['cover_image'],100,100);
        }
        $result = array(
            "customer" => $customers,
            "events"    => $events
        );
    return $result;
    }
    /*
     * 获取动态列表
     * */
    public function  actionGethptrends(){
        $customer_id = \Yii::$app->request->post("customer_id");
        //$customer_id = 41621;
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);

        $offset = isset($count[0]) ? $count[0] : 0;
        $limit = isset($count[1]) ? $count[1] : 20;

        //获取A用户的动态key
        $trendsinbox = \Yii::$app->redis->zrevrange("TrendsInbox_".$customer_id,$offset,$limit-1);
        //print_r($trendsinbox);exit;// Array ( [0] => Trends:exp_41 )
        if(!empty($trendsinbox)){
            foreach($trendsinbox as $trendskey){
                $list = [];
                $key = \Yii::$app->redis->hget($trendskey,'key'); // trendskey like Customer:314  Exp:41  Group:1  Events:1
                if(!empty($key)){
                    $c_id = \Yii::$app->redis->hget($trendskey,'who');
                    $action = \Yii::$app->redis->hget($trendskey,'action');

                    $what = Helper::getKeyValue($key);
                    $who = Helper::getKeyValue("Customer:".$c_id);
                    if(!isset($who['age'])){
                        $age = date("Y") - date("Y",strtotime($who['birthday']));
                        $who['age'] = strval($age);
                    }

                    if(!empty($what)){
                        if(isset($what['type_name_id']) && !empty($what['type_name_id'])){
                            $what['num_comment'] = ClubComment::find()->where(["type_name_id"=>$what['type_name_id'],"content_id"=>$what['item_id']])->count();
                            if($what['type_name_id'] == 3){
                                $what['num_events'] = ClubEvents::find()->where(['by_group_id'=>$what['item_id']])->count();
                                $what['num_member'] = ClubGroupMember::find()->where(['group_id'=>$what['item_id']])->count();
                            }elseif($what['type_name_id'] == 4){
                                $what['num_member'] = ClubEventsMember::find()->where(['events_id'=>$what['item_id']])->count();

                                $total = 0;
                                $event_fee = ClubEventsFee::find()->where(['events_id'=>$what['item_id']])->all();
                                foreach($event_fee as $fee){
                                    $total = $total + $fee['price'];
                                }
                                $what['fee_total'] = strval($total);

                            }

                        }
                    }
                    $list = array(
                        'customer' => $who,
                        'action' => $action,
                    );

                    foreach($what as $k => $v){
                        $list[$k] = $v;
                    }

                    if($what['type_name_id'] == 4){
                        if(isset($what['cover_image'])){
                            $list['cover_image'] = Image::resize($what['cover_image'],0,0);
                        }else{
                            $list['cover_image'] = Image::resize('noimage.jpg',0,0);
                        }
                    }elseif($what['type_name_id'] == 3){
                        if(isset($what['logo'])){
                            $list['cover_image'] = Image::resize($what['logo'],0,0);
                        }else{
                            $list['cover_image'] = Image::resize('noimage.jpg',0,0);
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
                        $list['image_array'] = $img_array;
                        if(isset($what['cover_image'])){
                            $list['cover_image'] = Image::resize($what['cover_image'],0,0);
                        }else{
                            $list['cover_image'] = Image::resize('noimage.jpg',0,0);
                        }

                    }else{
                        if(isset($what['cover_image'])){
                            $list['cover_image'] = Image::resize($what['cover_image'],0,0);
                        }else{
                            $list['cover_image'] = Image::resize('noimage.jpg',0,0);
                        }
                    }
                    if(!empty($what) && !empty($who) && !empty($action)){
                        $result[] = $list;
                    }
                }
            }

            return $result;


        }else{
            return ;
        }

    }
    /*
     * 获取与我相关列表
     * */
    public function actionGetaboutme(){
        $customer_id = \Yii::$app->request->get("customer_id");
       // $customer_id = 314;
        //获取A用户的动态key
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);

        $offset = isset($count[0]) ? $count[0] : 0;
        $limit = isset($count[1]) ? $count[1] : 20;
        $trendsinbox = \Yii::$app->redis->zrevrange("AboutmeInbox_".$customer_id,$offset,$limit-1);
        //print_r($trendsinbox);exit;// Array ( [0] => Trends:exp_41 )
        if(!empty($trendsinbox)){
            $list = array();
            foreach($trendsinbox as $trendskey){
                $key = \Yii::$app->redis->hget($trendskey,'key'); // like Customer:314  Exp:41  Group:1  Events:1 Comment
                if(!empty($key)){
                    $c_id = \Yii::$app->redis->hget($trendskey,'who');
                    $action = \Yii::$app->redis->hget($trendskey,'action');

                    $what = Helper::getKeyValue($key);

                    $who = Helper::getKeyValue("Customer:".$c_id);
                    if(!empty($what)){
                        if(isset($what['type_name_id']) && !empty($what['type_name_id'])){
                            if(!isset($what['item_id'])){
                                $item_id = $what['content_id'];
                            }else{
                                $item_id = $what['item_id'];
                            }
                            $what['num_comment'] = ClubComment::find()->where(["type_name_id"=>$what['type_name_id'],"content_id"=>$item_id])->count();
                        }
                    }
                    $list = array(
                        'customer' => $who,
                        'action' => $action,
                    );
                    if(!empty($what)){
                        foreach($what as $k => $v){
                            $list[$k] = $v;
                            if($k == 'type_name_id'){
                                if($v == 3){
                                    if(isset($what['logo'])){
                                        $list['logo'] = Image::resize($what['logo'],0,0);
                                    }else{
                                        $list['logo'] = Image::resize('noimage.jpg',0,0);
                                    }

                                }elseif($v == 1|| $v == 4 ){
                                    if(isset($what['cover_image'])){
                                        $list['cover_image'] = Image::resize($what['cover_image'],0,0);
                                    }else{
                                        $list['cover_image'] = Image::resize('noimage.jpg',0,0);
                                    }

                                }
                            }
                        }
                    }

                    $reslut[] = $list;
                }
            }

            return $reslut;
        }else{
            return ;
        }
    }

    /*
     * 分页获取数据；
     *  @model 需要启用的model模块；
     *  @data 筛选条件；
     * */
    private function actionGetlist($model,$data = array()){
        $page = \Yii::$app->request->post("page"); //当前页数
        $page = intval($page);
        $pagesize = \Yii::$app->request->post("pagesize");
        $pagesize = intval($pagesize);

        if($page < 1 || !is_int($page)){
            $page = 1;
        }
        if(empty($pagesize) || !is_int($pagesize)){
            $pagesize = 10; //每页10条数据
        }

        $offset = ($page - 1) * $pagesize;
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
}
