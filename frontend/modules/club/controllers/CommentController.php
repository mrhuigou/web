<?php
namespace frontend\modules\club\controllers;
use api\models\V1\ClubActivity;
use api\models\V1\ClubTry;
use api\models\V1\ClubUserComment;
use api\models\V1\ClubVote;
use api\models\V1\ClubVoteItem;
use yii\data\Pagination;
use yii\web\Controller;

class CommentController extends Controller
{
    public function actionIndex()
    {
        $query=ClubUserComment::find()->where(['type'=>\Yii::$app->request->get('type'),'type_id'=>\Yii::$app->request->get('type_id')]);
        $pages = new Pagination(['totalCount' =>$query->count(),'pageSize' => '3']);
        $model = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->renderPartial('comment',['model'=>$model,'pages' => $pages]);
    }
    public function actionSubmit(){
        if(\Yii::$app->user->isGuest){
            return "请先登陆";
        }
        if(!\Yii::$app->request->post('content')){
            return "内容不能为空";
        }
        $type=\Yii::$app->request->post('type');
        $type_id=\Yii::$app->request->post('type_id');
        $customer_id=\Yii::$app->user->getId();
        $content=\Yii::$app->request->post('content');
        $images='';
        $address='未知';
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
            return "save data error!";
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
    }
}
