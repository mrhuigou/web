<?php
namespace frontend\modules\club\controllers;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use api\models\V1\ClubActivity;
use api\models\V1\ClubTry;
use api\models\V1\ClubUserComment;
use api\models\V1\ClubUserLike;
use api\models\V1\ClubVote;
use api\models\V1\ClubVoteItem;

class LikeController extends Controller
{
    public function actionIndex()
    {
        $model=ClubTry::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->orderBy([ 'sort_order' => SORT_ASC]),
            'pagination' => [
                'pagesize' => '10',
            ]
        ]);
        return $this->render('index',['model'=>$model,'dataProvider'=>$dataProvider]);
    }
    public function actionSubmit(){
        if(\Yii::$app->user->isGuest){
            return "请先登陆";
        }
        $type=\Yii::$app->request->post('type');
        $type_id=\Yii::$app->request->post('type_id');
        $customer_id=\Yii::$app->user->getId();
        if($model=ClubUserLike::findOne(['customer_id'=>$customer_id,'type'=>$type,'type_id'=>$type_id])){
            return '已经点赞过了';
        }
        $model=new ClubUserLike();
        $model->type=$type;
        $model->type_id=$type_id;
        $model->customer_id=$customer_id;
        $model->creat_at=date('Y-m-d H:i:s',time());
        if(!$model->save()){
            return '数据保存异常';
        }
        if($type=='try'){
            $mod=ClubTry::findOne(['id'=>$type_id]);
            if($mod){
                $mod->like_count= ($mod->like_count)+1;
                $mod->save();
            }
        }
        if($type=='activity'){
            $mod=ClubActivity::findOne(['id'=>$type_id]);
            if($mod){
                $mod->like_count= ($mod->like_count)+1;
                $mod->save();
            }
        }
        if($type=='vote'){
            $mod=ClubVote::findOne(['id'=>$type_id]);
            if($mod){
                $mod->like_count= ($mod->like_count)+1;
                $mod->save();
            }
        }
        if($type=='vote_item'){
            $mod=ClubVoteItem::findOne(['id'=>$type_id]);
            if($mod){
                $mod->like_count= ($mod->like_count)+1;
                $mod->save();
            }
        }
        if($type=='comment'){
            $mod=ClubUserComment::findOne(['id'=>$type_id]);
            if($mod){
                $mod->like_count= ($mod->like_count)+1;
                $mod->save();
            }
        }
    }
}
