<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/1
 * Time: 16:58
 */
namespace api\controllers\club\v2;
use api\models\V1\ClubVote;
use api\models\V1\ClubVoteItem;
use common\component\image\Image;
use \yii\rest\Controller;
use api\controllers\club\v2\filters\AccessControl;
use common\component\response\Result;
use yii\helpers\ArrayHelper;
class VoteController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => AccessControl::className()
            ],
        ]);
    }
    public function actionList(){
        $data=[];
        $model=ClubVote::find()->where(['status'=>1]);
        $offset=\Yii::$app->request->post('offset')?\Yii::$app->request->post('offset'):0;
        $limit=\Yii::$app->request->post('limit')?\Yii::$app->request->post('limit'):5;
        $data['totalCount']=$model->count();
        $model = $model->offset($offset)->limit($limit)->all();
        $data['list']=[];
        if($model){
            foreach($model as $value){
                $status=1;
                if(time()>strtotime($value->end_datetime)){
                    $status=0;
                }
                if(time()<strtotime($value->begin_datetime)){
                    $status=2;
                }
                $data['list'][]=[
                    'id'=>$value->id,
                    'title'=>$value->title,
                    'image'=>Image::resize($value->image,100,100,9),
                    'begin_datetime'=>$value->begin_datetime,
                    'end_datetime'=>$value->end_datetime,
                    'join_type'=>$value->join_type,
                    'user_number'=>$value->userCount,
                    'click_count'=>$value->click_count,
                    'like_count'=>$value->like_count,
                    'comment_count'=>$value->comment_count,
                    'share_count'=>$value->share_count,
                    'status'=>$status,
                ];
            }
        }
        return Result::OK($data);
    }
    public function actionDetail(){
        if($model=ClubVote::findOne(['id'=>\Yii::$app->request->post('vote_id')])){
            $status=1;
            if(time()>strtotime($model->end_datetime)){
                $status=0;
            }
            if(time()<strtotime($model->begin_datetime)){
                $status=2;
            }
            $data=[
                'id'=>$model->id,
                'title'=>$model->title,
                'description'=>$model->description,
                'image'=>Image::resize($model->image,100,100,9),
                'begin_datetime'=>$model->begin_datetime,
                'end_datetime'=>$model->end_datetime,
                'user_number'=>$model->userCount,
                'click_count'=>$model->click_count,
                'like_count'=>$model->like_count,
                'comment_count'=>$model->comment_count,
                'share_count'=>$model->share_count,
                'status'=>$status,
            ];
            return Result::OK($data);
        }else{
            return Result::Error('找不到数据');
        }
    }
    public function actionItem(){
        $data=[];
        $model=ClubVoteItem::find()->where(['vote_id'=>\Yii::$app->request->post('vote_id')])->all();
        if($model){
            foreach($model as $value){
                $data[]=[
                    'id'=>$value->id,
                    'title'=>$value->title,
                    'image'=>Image::resize($value->image,100,100,9),
                    'click_count'=>$value->click_count,
                    'like_count'=>$value->like_count,
                    'comment_count'=>$value->comment_count,
                    'share_count'=>$value->share_count,
                    'like_status'=>$value->likeStatus,
                ];
            }
        }
        return Result::OK($data);
    }
    public function actionItemDetail(){
        if($model=ClubVoteItem::findOne(['id'=>\Yii::$app->request->post('vote_item_id')])){
            $data=[
                'id'=>$model->id,
                'title'=>$model->title,
                'description'=>$model->description,
                'image'=>Image::resize($model->image,100,100,9),
                'begin_datetime'=>$model->begin_datetime,
                'end_datetime'=>$model->end_datetime,
                'click_count'=>$model->click_count,
                'like_count'=>$model->like_count,
                'comment_count'=>$model->comment_count,
                'share_count'=>$model->share_count,
                'like_status'=>$model->likeStatus,
            ];
            return Result::OK($data);
        }else{
            return Result::Error('找不到数据');
        }
    }


    public function actionApply(){

    }

}