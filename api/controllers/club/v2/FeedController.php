<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/8/20
 * Time: 9:56
 */
namespace api\controllers\club\v2;
use api\controllers\club\v2\filters\AccessControl;
use api\models\V1\ClubUserComment;
use yii\helpers\Json;
use \yii\rest\Controller;
use common\component\image\Image;
use common\component\response\Result;
use yii\helpers\ArrayHelper;
class FeedController extends Controller
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
        $model=ClubUserComment::find()->where(['type'=>['try','activity'],'status'=>1])->orderBy([ 'creat_at' => SORT_DESC]);
        $offset=\Yii::$app->request->post('offset')?\Yii::$app->request->post('offset'):0;
        $limit=\Yii::$app->request->post('limit')?\Yii::$app->request->post('limit'):5;
        $data['totalCount']=$model->count();
        $model = $model->offset($offset)->limit($limit)->all();
        $data['list']=[];
        if($model){
            foreach($model as $value){
                $reply=[];
                if($value->reply){
                    foreach($value->reply as $v){
                        $reply[]=[
                            'id'=>$v->id,
                            'customer_id'=>$v->customer_id,
                            'nickname'=>$v->customer->nickname,
                            'photo'=>Image::resize($v->customer->photo,100,100),
                            'content'=>$v->content,
                            'images'=>$v->images,
                            'creat_at'=>$v->creat_at
                        ];
                    }
                }
                $like_user=[];
                if($value->userLike){
                    foreach($value->userLike as $user){
                        $like_user[]=[
                            'customer_id'=>$user->customer_id,
                            'nickname'=>$user->customer->nickname,
                        ];
                    }
                }
                $tag=[];
                if($value->tag){
                    foreach($value->tag as $t){
                        $tag[]=[
                            'id'=>$t->tag_id,
                            'name'=>$t->tag->name,
                        ];
                    }
                }
                $data['list'][]=[
                    'id'=>$value->id,
                    'type'=>$value->type,
                    'type_id'=>$value->type_id,
                    'customer_id'=>$value->customer_id,
                    'nickname'=>$value->customer->nickname,
                    'photo'=>Image::resize($value->customer->photo,100,100),
                    'content'=>$value->content,
                    'images'=>$value->images?$value->images:'',
                    'address'=>$value->address?$value->address:'',
                    'reply'=>$reply,
                    'tag'=>$tag,
                    'like_count'=>$value->like_count,
                    'like_user'=>$like_user,
                    'like_status'=>$value->likeStatus,
                    'creat_at'=>$value->creat_at
                ];
            }
        }
        return Result::OK($data);
    }
    public function actionUserList(){
        if(!\Yii::$app->request->post('customer_id')){
            Result::Error('缺少参数customer_id');
        }
        $model=ClubUserComment::find()->where(['type'=>['try','activity'],'status'=>1,'customer_id'=>\Yii::$app->request->post('customer_id')])->orderBy([ 'creat_at' => SORT_DESC]);
        $offset=\Yii::$app->request->post('offset')?\Yii::$app->request->post('offset'):0;
        $limit=\Yii::$app->request->post('limit')?\Yii::$app->request->post('limit'):5;
        $data['totalCount']=$model->count();
        $model = $model->offset($offset)->limit($limit)->all();
        $data['list']=[];
        if($model){
            foreach($model as $value){
                $reply=[];
                if($value->reply){
                    foreach($value->reply as $v){
                        $reply[]=[
                            'id'=>$v->id,
                            'customer_id'=>$v->customer_id,
                            'nickname'=>$v->customer->nickname,
                            'photo'=>Image::resize($v->customer->photo,100,100),
                            'content'=>$v->content,
                            'images'=>$v->images?$v->images:'',
                            'creat_at'=>$v->creat_at
                        ];
                    }
                }
                $like_user=[];
                if($value->userLike){
                    foreach($value->userLike as $user){
                        $like_user[]=[
                            'customer_id'=>$user->customer_id,
                            'nickname'=>$user->customer->nickname,
                        ];
                    }
                }
                $tag=[];
                if($value->tag){
                    foreach($value->tag as $t){
                        $tag[]=[
                            'id'=>$t->tag_id,
                            'name'=>$t->tag->name,
                        ];
                    }
                }
                $data['list'][]=[
                    'id'=>$value->id,
                    'type'=>$value->type,
                    'type_id'=>$value->type_id,
                    'customer_id'=>$value->customer_id,
                    'nickname'=>$value->customer->nickname,
                    'photo'=>Image::resize($value->customer->photo,100,100),
                    'content'=>$value->content,
                    'images'=>$value->images?$value->images:'',
                    'address'=>$value->address?$value->address:'',
                    'reply'=>$reply,
                    'tag'=>$tag,
                    'like_count'=>$value->like_count,
                    'like_user'=>$like_user,
                    'like_status'=>$value->likeStatus,
                    'creat_at'=>$value->creat_at
                ];
            }
        }
        return Result::OK($data);
    }
}