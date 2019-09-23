<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/1
 * Time: 16:58
 */
namespace api\controllers\club\v2;
use api\models\V1\ClubActivity;
use api\models\V1\ClubTry;
use api\models\V1\ClubUserComment;
use api\models\V1\ClubUserLike;
use api\models\V1\ClubVote;
use api\models\V1\ClubVoteItem;
use \yii\rest\Controller;
use common\component\response\Result;
use api\controllers\club\v2\filters\AccessControl;
use yii\helpers\ArrayHelper;
class LikeController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => AccessControl::className()
            ],
        ]);
    }
    public function actionSubmit(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        $type=\Yii::$app->request->post('type');
        $type_id=\Yii::$app->request->post('type_id');
        $customer_id=\Yii::$app->user->getId();
        if($model=ClubUserLike::findOne(['customer_id'=>$customer_id,'type'=>$type,'type_id'=>$type_id])){
            return Result::Error('已经点赞过了');
        }
        $model=new ClubUserLike();
        $model->type=$type;
        $model->type_id=$type_id;
        $model->customer_id=$customer_id;
        $model->creat_at=date('Y-m-d H:i:s',time());
        if(!$model->save()){
            return Result::Error('数据保存异常');
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



        return Result::OK();
    }
}