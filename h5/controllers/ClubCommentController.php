<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/10
 * Time: 15:35
 */
namespace h5\controllers;
use api\models\V1\ClubActivity;
use api\models\V1\ClubTry;
use api\models\V1\ClubUserComment;
use api\models\V1\ClubVote;
use api\models\V1\ClubVoteItem;
use common\component\Helper\Helper;
use h5\models\ClubCommentForm;
use yii\data\ActiveDataProvider;

class ClubCommentController extends \yii\web\Controller
{
    public function actions(){
        return [
            'download' => [
                'class' => 'h5\widgets\Wx\ImageAction',
            ]
        ];
    }
    public function actionIndex(){
        $type=\Yii::$app->request->get('type');
        $type_id=\Yii::$app->request->get('type_id');
        $model=ClubUserComment::find()->where(['type'=>$type,'type_id'=>$type_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->orderBy('creat_at desc'),
            'pagination' => [
                'pagesize' => '8',
            ]
        ]);
        return $this->render('index',['model'=>$model,'dataProvider'=>$dataProvider]);
    }
    public function actionGetAddress(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }
        $lat=\Yii::$app->request->get('latitude');
        $lng=\Yii::$app->request->get('longitude');
        $gps=Helper::getGps($lat,$lng,true);
        return Helper::getAddressByGps(implode(',',$gps));
    }
    public function actionApply(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }
        $type=\Yii::$app->request->get('type');
        $type_id=\Yii::$app->request->get('type_id');
        $model=new ClubCommentForm($type,$type_id);
        if($model->load(\Yii::$app->request->post()) && $model->save()){
         return $this->redirect(['club-'.$type.'/detail','id'=>$type_id]);
        }
        return $this->render('apply',['model'=>$model]);
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