<?php

namespace api\controllers\club\v1;

use api\models\V1\ClubComment;
use api\models\V1\ClubEvents;
use api\models\V1\ClubExperience;
use api\models\V1\ClubGroup;
use api\modules\oauth2\filters\auth\CompositeAuth;
use common\component\Helper\Helper;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;

class CommentController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionGetcommentsbytype(){
        $type_name_id = \Yii::$app->request->post("type");
        $item_id = \Yii::$app->request->post("id");
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);

        $data = [];
        $data['where'] = ['type_name_id'=>$type_name_id,'content_id'=>$item_id];
        $data['orderby'] = 'create_time DESC';
        $data['offset'] = isset($count[0]) ? $count[0] : 0;
        $data['limit'] = isset($count[1]) ? $count[1] : 20;

        $model = ClubComment::find();
        $result = $this->actionFormatdata($model,$data);

        foreach($result as $key =>$comment){
            $result[$key]['customer'] = Helper::getCustomerformat($comment['customer_id']);
        }
        $result = Helper::genTree($result,'comment_id','reference_id','children');
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
    public function actionInsertcomment(){
        $type_name_id = \Yii::$app->request->post("type");
        $content_id =  \Yii::$app->request->post("id");
        $content = \Yii::$app->request->post("content");

        $customer_id = \Yii::$app->request->post("customer_id");
        $referebce_id = \Yii::$app->request->post("parents_id"); //回复某评论的 评论id


        $comment = new ClubComment();
        $comment->customer_id = $customer_id;
        $comment->type_name_id = $type_name_id;
        $comment->content_id = $content_id; //活动、体验、圈子等的id
        $comment->reference_id = $referebce_id;
        $comment->create_time = date("Y-m-d H:i:s");
        $comment->content = $content;

        $comment->save();

        $comment_id = $comment->comment_id;

         Helper::setComment($comment);


        //
        if(!empty($referebce_id)){
            $info = ClubComment::findOne($referebce_id);
            $action = '回复';
            $data = [
                'customer_id' => $customer_id,// 评论作者
                'action'     => $action,
                'type_name_id'=> 6, //评论
                'item_id'   => $comment_id,//评论id
            ];

        }else{
            $action = '评论';
            if($type_name_id == 1){
                $info = ClubExperience::findOne($content_id);
                $data = [
                    'type_name_id'=> 6, //评论
                    'item_id'   => $comment_id,//评论id
                    'customer_id' => $customer_id,// 评论作者
                    'action'     => $action,
                    'exp_id'    => $info->exp_id,
                ];
                if($customer_id != $info->customer_id){
                    Helper::setAboutme($info->customer_id,$data); //设置与我相关 “我”即活动 圈子 体验的作者
                }
            }elseif($type_name_id == 3){
                $info = ClubGroup::findOne($content_id);
                $data = [
                    'type_name_id'=> 6, //评论
                    'item_id'   => $comment_id,//评论id
                    'customer_id' => $customer_id,// 评论作者
                    'action'     => $action,
                    'group_id'    => $info->group_id,
                ];
                if($customer_id != $info->customer_id){
                    Helper::setAboutme($info->customer_id,$data); //设置与我相关 “我”即活动 圈子 体验的作者
                }
            }elseif($type_name_id == 4){
                $info = ClubEvents::findOne($content_id);
                $data = [
                    'type_name_id'=> 6, //类型：评论
                    'item_id'   => $comment_id,//评论id
                    'customer_id' => $customer_id,// 评论作者
                    'action'     => $action,
                    'events_id'    => $info->events_id,
                ];
                if($customer_id != $info->by_customer_id){
                    Helper::setAboutme($info->by_customer_id,$data); //设置与我相关 “我”即活动 圈子 体验的作者
                }
            }
        }

        /*我的圈子 活动 体验 等被评论了*/
        Helper::setAboutme($customer_id,$data); //设置与我相关 “我”即活动 圈子 体验的作者
        $msg['msg'] = "success";
        return $msg;
    }

}
