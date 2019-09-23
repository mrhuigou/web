<?php

namespace api\controllers\club\v1;

use api\models\V1\ClubComment;
use api\models\V1\ClubExperience;
use api\models\V1\ClubRelation;
use api\models\V1\Customer;
use common\component\Helper\Helper;
use common\component\image\Image;

class ExperienceController extends \yii\rest\Controller
{
    public function actionIndex()
    {

    }
    /*
     * 获取 体验详情
     * */
    public function  actionGettrendsbyid(){
        $experience_id = \Yii::$app->request->post('tid');
        $result = ClubExperience::findOne($experience_id);

        $result_data = array();
        if(!empty($result)){
            $customer = Helper::getCustomerformat($result->customer_id);
            $cover_image = Image::resize($result->cover_image,0,0);
            $img_array = array();
            if(!empty($result->image_array)){
                $image_array = unserialize($result->image_array);
                if(is_array($image_array)) {
                    foreach($image_array as $img){
                        $img_array[] = Image::resize($img['image'],0,0);
                    }
                }
            }

            $result_data['customer'] = $customer;
            $result_data['cover_image'] = $cover_image;
            $result_data['image_array'] = $img_array;
            $result_data['content'] = $result['content'];
            $result_data['exp_id'] = $result['exp_id'];
            $result_data['num_comment'] =ClubComment::find()->where(["type_name_id"=>1,"content_id"=>$experience_id])->count();
            $comments = ClubComment::find()->where(["type_name_id"=>1,"content_id"=>$experience_id,"is_del"=>0])->offset(0)->limit(5)->all();
            $coms = array();
            foreach($comments as $key => $comment){
                $coms[$key]['customer'] = Helper::getCustomerformat($comment->customer_id);
                $coms[$key]['content'] = $comment->content;
                $coms[$key]['create_time'] = $comment->create_time;
                $coms[$key]['comment_id'] = $comment->comment_id;
            }
            $result_data['comments'] = $coms;

        }
        return $result_data;
    }
    /*
     * 获取 某活动的体验；
     * */
    public function actionGeteventtrends(){
        $event_id = \Yii::$app->request->post("aid");
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);

        $data = [];
        $data['where'] = ['event_id'=>$event_id];
        $data['orderby'] = 'create_time DESC';
        $data['offset'] = isset($count[0]) ? $count[0] : 0;
        $data['limit'] = isset($count[1]) ? $count[1] : 20;
        $model = ClubExperience::find();
        $result = $this->actionFormatdata($model,$data);
        foreach($result as $key => $v){
            $result[$key]['image_array'] = array();
            $result[$key]['cover_image'] = Image::resize($v['cover_image'],0,0);
            $result[$key]['customer'] = Helper::getCustomerformat($v['customer_id']);
        }
        return $result;
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
/*
 * 插入体验数据
 * */
    public function actionInserttrend(){

        $customer_id = \Yii::$app->request->post('customer_id');
        $content = \Yii::$app->request->post('content');
        $cover_image = \Yii::$app->request->post('cover_image');
        $event_id = \Yii::$app->request->post('event_id');
        $image_string = \Yii::$app->request->post('image_array');
        if(!empty($image_string)){
            $image_array = explode(",",$image_string);
            $imgs_array = [];
            foreach($image_array as $key => $v){
                $imgs_array[$key]['image'] = $v;
                $imgs_array[$key]['des'] = "";
            }
            $imgs = serialize($imgs_array);
        }else{
            $imgs = serialize("");
        }
        if(empty($event_id)){
            $event_id = 0;
        }
        if(empty($customer_id)){
            return 'customer_id不存在';
            exit;
        }
        if(empty($cover_image)){
            return '主图不存在';
            exit;
        }
        $experience = new ClubExperience();
        $experience->customer_id = $customer_id;
        $experience->content = $content;
        $experience->event_id = $event_id;
        $experience->cover_image = $cover_image;
        $experience->image_array = $imgs;
        $experience->create_time = date("Y-m-d H:i:s");
        $experience->last_update_time = date("Y-m-d H:i:s");

       if( $experience->save()){
           $data = [
               'type_name_id'=> 1,
               'item_id'   => $experience->exp_id,
               'customer_id' => $customer_id,
               'events_id'  => $experience->event_id,
               'action'     => '发表体验'
           ];

           Helper::setTrends($customer_id,$data); //加入我的动态中
           Helper::setMyPage($customer_id,$data); //加入我的个人主页中

          // $myfriends = ClubRelation::find()->where(['customer_id'=> $customer_id ,'status'=>1])->all();
           //foreach($myfriends as $friend){
           //    Helper::setTrends($friend->friend_customer_id,$data); //告知我的朋友们，我发表体验
           //}
           return json_encode('success');
       }else{
           return json_encode('false');
       }
    }

}
