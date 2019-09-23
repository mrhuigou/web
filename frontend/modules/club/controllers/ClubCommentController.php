<?php
namespace frontend\modules\club\controllers;
use api\models\V1\ClubActivity;
use api\models\V1\ClubComment;
use api\models\V1\ClubTry;
use api\models\V1\ClubUserComment;
use api\models\V1\ClubVote;
use api\models\V1\ClubVoteItem;
use yii\data\Pagination;
use yii\web\Controller;

class ClubCommentController extends Controller
{
    public function actionIndex()
    {
            $query = ClubComment::find()->where(['type_name_id'=>\Yii::$app->request->get("type_name_id"),'content_id'=>\Yii::$app->request->get("content_id"),'reference_id'=>0])->orderBy("create_time desc");
        //$query=ClubUserComment::find()->where(['type'=>\Yii::$app->request->get('type'),'type_id'=>\Yii::$app->request->get('type_id')]);
        $pages = new Pagination(['totalCount' =>$query->count(),'pageSize' => '3']);
        $model = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->renderPartial('/clubcomment/club-comment',['model'=>$model,'pages' => $pages]);
    }
    public function actionSubmit(){
        if(\Yii::$app->user->isGuest){
            return "请先登陆";
        }
        if(!\Yii::$app->request->post('content')){
            return "内容不能为空";
        }
        $reference_id = 0;
        if(\Yii::$app->request->post("reference_id")){
            $reference_id = \Yii::$app->request->post("reference_id");
        }
        $type_name_id = \Yii::$app->request->post('type_name_id');
        $content_id = \Yii::$app->request->post('content_id');
        $customer_id = \Yii::$app->user->getId();
        $content = \Yii::$app->request->post('content');

        $model=new ClubComment();
        $model->customer_id = $customer_id;
        $model->type_name_id = $type_name_id;
        $model->content_id = $content_id;
        $model->content = $content;
        $model->reference_id = $reference_id;
        $model->is_pop = 0;
        $model->is_del = 0;
        $model->create_time = date("Y-m-d H:i:s",time());
        if(!$model->save()){
            return "save data error!";
        }
    }
}
