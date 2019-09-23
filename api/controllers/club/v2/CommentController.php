<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/1
 * Time: 16:58
 */
namespace api\controllers\club\v2;
use api\controllers\club\v2\filters\AccessControl;
use api\models\V1\ClubActivity;
use api\models\V1\ClubTry;
use api\models\V1\ClubUserComment;
use api\models\V1\ClubUserCommentTag;
use api\models\V1\ClubVote;
use api\models\V1\ClubVoteItem;
use common\component\image\Image;
use common\component\response\Result;
use \yii\rest\Controller;
use yii\helpers\ArrayHelper;
class CommentController extends Controller
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
        $type=\Yii::$app->request->post('type');
        $type_id=\Yii::$app->request->post('type_id');
        $model=ClubUserComment::find()->where(['type'=>$type,'type_id'=>$type_id])->orderBy('creat_at desc');
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
    public function actionSubmit(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        $type=\Yii::$app->request->post('type');
        $type_id=\Yii::$app->request->post('type_id');
        $customer_id=\Yii::$app->user->getId();
        $content=\Yii::$app->request->post('content');
        $images=\Yii::$app->request->post('images');
        $address=\Yii::$app->request->post('address');
        $model=new ClubUserComment();
        $model->customer_id=$customer_id;
        $model->type=$type;
        $model->type_id=$type_id;
        $model->content=$content;
        $model->images=$images;
        $model->address=$address;
        $model->status=1;
        $model->creat_at=date("Y-m-d H:i:s",time());
        if(!$model->save()){
            Result::Error("save data error!");
        }
        if($tags=\Yii::$app->request->post('tag')){
            $t=explode(',',$tags);
                foreach($t as $v){
                    $comment_log=new ClubUserCommentTag();
                    $comment_log->comment_id=$model->id;
                    $comment_log->tag_id=$v;
                    $comment_log->save();
                }
        }
        if($type=='try'){
            $mod=ClubTry::findOne(['id'=>$type_id]);
            if($mod){
                $mod->comment_count= ($mod->comment_count)+1;
                $mod->save();
            }
        }
        if($type=='activity'){
            $mod=ClubActivity::findOne(['id'=>$type_id]);
            if($mod){
                $mod->comment_count= ($mod->comment_count)+1;
                $mod->save();
            }
        }
        if($type=='vote'){
            $mod=ClubVote::findOne(['id'=>$type_id]);
            if($mod){
                $mod->comment_count= ($mod->comment_count)+1;
                $mod->save();
            }
        }
        if($type=='vote_item'){
            $mod=ClubVoteItem::findOne(['id'=>$type_id]);
            if($mod){
                $mod->comment_count= ($mod->comment_count)+1;
                $mod->save();
            }
        }
        return Result::OK(['comment_id'=>$model->id]);
    }
}