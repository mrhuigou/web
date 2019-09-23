<?php

namespace api\controllers\club\v1;

use api\models\V1\ClubEvents;
use api\models\V1\ClubEventsMember;
use api\models\V1\ClubExperience;
use api\models\V1\ClubGroup;
use api\models\V1\ClubGroupMember;
use api\models\V1\Customer;
use api\models\V1\Image;
use common\component\Helper\Helper;

class SearchController extends \yii\rest\Controller
{
    public function actionSearch()
    {
        // type 1：体验 3:圈子 4:活动 5：用户
        $type = \Yii::$app->request->post("type");
        $keyword = \Yii::$app->request->post("keyword");
        $count = \Yii::$app->request->post("count");
        $count = explode('-',$count);

        $offset = isset($count[0]) ? $count[0] : 0;
        $limit = isset($count[1]) ? $count[1] : 5;
        $data = [];
        if(!empty($type)){
            if($type == "1"){
                $model = ClubExperience::find();
                $model->orFilterWhere(['like','title',$keyword])->orFilterWhere(['like','content',$keyword]);
                $results = $model->offset($offset)->limit($limit)->all();
                $count = 0;
                if(!empty($results)){

                    foreach($results as $key => $v){
                        $data[$count]['exp_id'] = strval($v->exp_id);
                        $data[$count]['title'] = $v->title;
                        $data[$count]['cotent'] = $v->content;
                        $data[$count]['customer'] = Helper::getCustomerformat($v->customer_id);
                        $data[$count]['cover_image'] = \common\component\image\Image::resize($v->cover_image,0,0);
                        $data[$count]['create_time'] = $v->create_time;
                        $data[$count]['last_update_time'] = $v->last_update_time;
                        $count ++;
                    }
                }
            }elseif($type == "3"){
                $model = ClubGroup::find();
                $model->orFilterWhere(['like','title',$keyword])->orFilterWhere(['like','description',$keyword]);
                $results = $model->offset($offset)->limit($limit)->all();
                if(!empty($results)){
                    $count = 0;
                    foreach($results as $key => $v){
                        $member_num = 1;
                        $member_num = ClubGroupMember::find()->where(["group_id"=>$v->group_id])->count(); //成员数量
                        $data[$count]['group_id'] = strval($v->group_id);
                        $data[$count]['member_num'] = $member_num;
                        $data[$count]['title'] = $v->title;
                        $data[$count]['description'] = $v->description;
                        $data[$count]['customer'] = Helper::getCustomerformat($v->customer_id);
                        $data[$count]['cover_image'] = \common\component\image\Image::resize($v->logo,0,0);
                        $data[$count]['create_time'] = $v->created_at;
                        $data[$count]['last_update_time'] = $v->updated_at;
                        $data[$count]['num_events'] = strval(ClubEvents::find()->where(['by_group_id'=>$v->group_id])->count());
                        $data[$count]['num_member'] = strval(ClubGroupMember::find()->where(['group_id'=>$v->group_id,'status'=>1])->count());
                    $count++;

                    }
                }
            }elseif($type == "4"){
                $model = ClubEvents::find();
                $model->orFilterWhere(['like','title',$keyword])->orFilterWhere(['like','description',$keyword]);
                $results = $model->offset($offset)->limit($limit)->all();
                if(!empty($results)){
                    $count = 0;
                    foreach($results as $key => $v){
                        $data[$count]['events_id'] = strval($v->events_id);
                        $data[$count]['title'] = $v->title;
                        $data[$count]['description'] = $v->description;
                        $data[$count]['customer'] = Helper::getCustomerformat($v->by_customer_id);
                        $data[$count]['cover_image'] = \common\component\image\Image::resize($v->cover_image,0,0);
                        $data[$count]['create_time'] = $v->created_at;
                        $data[$count]['last_update_time'] = $v->updated_at;
                        $data[$count]['start_time'] = $v->start_time;
                        $data[$count]['end_time'] = $v->end_time;
                        $data[$count]['num_exps'] = strval(ClubExperience::find()->where(['event_id'=>$v->events_id])->count());
                        $data[$count]['num_member'] = strval(ClubEventsMember::find()->where(['events_id'=>$v->events_id,'status'=>1])->count());
                        $count++;
                    }
                }
            }elseif($type == "5"){
                $model = Customer::find();
                $model->orFilterWhere(['like','nickname',$keyword])->orFilterWhere(['like','firstname',$keyword])->orFilterWhere(['like','email',$keyword])->orFilterWhere(['like','telephone',$keyword]);
                $results = $model->offset($offset)->limit($limit)->all();
                if(!empty($results)){
                    $count= 0;
                    foreach($results as $key => $v){
                        $data[$count] = Helper::getCustomerformat($v->customer_id);
                        //$data['events_id'] = $v->events_id;
                        $count ++;
                    }
                }
            }else{
                return ;
            }
            //$result = $model->offset($offset)->limit($limit)->asArray()->all();
            return $data;
        }else{
            return ;
        }


    }

}
