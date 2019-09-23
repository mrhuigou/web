<?php

namespace h5\controllers;
use Yii;
use api\models\V1\Address;
use api\models\V1\ClubTry;
use api\models\V1\ClubTryUser;
use api\models\V1\ClubUserComment;
use api\models\V1\ClubUserInvite;
use api\models\V1\ClubUserInviteLog;
use h5\models\ClubTryForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ClubTryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $run_status=\Yii::$app->request->get('run_status');
        $query=[];
        if(!$run_status){
            $query=['and','begin_datetime<=Now()','end_datetime>=Now()'];
        }
        if($run_status == -1){
            $query='end_datetime<Now()';
        }
        if($run_status == 1){
            $query='begin_datetime>Now()';
        }
        $model=ClubTry::find();
        if($run_status == -1){
            $dataProvider = new ActiveDataProvider([
                'query' => $model->where($query)->andWhere(['status'=>1])->orderBy(['creat_at' => SORT_DESC]),
                'pagination' => [
                    'pagesize' => '8',
                ]
            ]);
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => $model->where($query)->andWhere(['status'=>1])->orderBy(['sort_order' => SORT_ASC, 'creat_at' => SORT_DESC]),
                'pagination' => [
                    'pagesize' => '8',
                ]
            ]);
        }
        return $this->render('index',['dataProvider'=>$dataProvider,'model'=>$model]);
    }
    public function actionDetail(){
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = Yii::$app->request->getAbsoluteUrl();
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
        $model=ClubTry::findOne(['id'=>\Yii::$app->request->get('id')]);
        if($model){
            if(!\Yii::$app->request->get('invite_code')){
                if(!$UserInvite=ClubUserInvite::findOne(['customer_id'=>\Yii::$app->user->getId(),'type'=>'try','type_id'=>$model->id])){
                    $UserInvite=new ClubUserInvite();
                    $UserInvite->customer_id=\Yii::$app->user->getId();
                    $UserInvite->type='try';
                    $UserInvite->type_id=$model->id;
                    $UserInvite->code=strval(time().rand(10000,999999));
                    $UserInvite->creat_at=date('Y-m-d H:i:s',time());
                    $UserInvite->save();
                }
                return $this->redirect(['/club-try/detail','id'=>$model->id,'invite_code'=>$UserInvite->code]);
            }
            $model->click_count= $model->click_count+1;
            $model->save();
            $comment=ClubUserComment::find()->where(['type'=>'try','type_id'=>$model->id])->orderBy('creat_at desc')->limit(5)->all();
            return $this->render('detail',['model'=>$model,'comments'=>$comment]);
        }else{
            throw new NotFoundHttpException("没有找到内容！");
        }
    }
    public function actionApply(){
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = Yii::$app->request->getAbsoluteUrl();
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
        if(\Yii::$app->user->identity->telephone_validate!=1){
            return $this->redirect(['/user/real-name','redirect'=>$url]);
        }
        if(!$address_id=\Yii::$app->session->get('checkout_address_id')){
           $address_id=\Yii::$app->user->identity->address_id;
        }
        $address=Address::findOne(['customer_id'=>\Yii::$app->user->getId(),'address_id'=>$address_id]);
        if(!$address){
            return $this->redirect(['/address/index','redirect'=>$url]);
        }
        $try_id=\Yii::$app->request->get('id');
        $model=new ClubTryForm($address_id,$try_id);
        if($model->load(\Yii::$app->request->post()) && $model->save()) {
          return $this->redirect(['/club-try/detail','id'=>$try_id]);
        }
        return $this->render('/club-try/apply',['address'=>$address,'model'=>$model]);
    }
    public function actionUser(){
        $model=ClubTryUser::find()->where(['try_id'=>\Yii::$app->request->get('id')]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->orderBy(['status' => SORT_DESC, 'creat_at' => SORT_DESC]),
            'pagination' => [
                'pagesize' => '8',
            ]
        ]);
        return $this->render('user',['dataProvider'=>$dataProvider,'model'=>$model]);
    }
    public function actionInvite(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
        }
        $invite=ClubUserInvite::findOne(['customer_id'=>\Yii::$app->user->getId(),'type'=>'try','type_id'=>\Yii::$app->request->get('id')]);
        if(!$invite){
            throw new NotFoundHttpException("没有找到活动！");
        }
        $model=ClubUserInviteLog::find()->where(['invite_id'=>$invite->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->orderBy('creat_at desc'),
            'pagination' => [
                'pagesize' => '8',
            ]
        ]);
        return $this->render('invite',['dataProvider'=>$dataProvider,'model'=>$model]);

    }
    public function actionMyJoin(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
        }
        //type = 1 已中奖的
        //type = 2 未中奖的
        //type =0 等待开奖的
        $type = \Yii::$app->request->get("type");
        $where=['jr_club_try_user.customer_id'=>\Yii::$app->user->getId()];
        if($type==1){
            $type=1;
            $where=array_merge($where,['jr_club_try.lottery_status'=>1,'jr_club_try_user.status'=>1]);
        }elseif($type==2){
            $type=2;
            $where=array_merge($where,['jr_club_try.lottery_status'=>1,'jr_club_try_user.status'=>0]);
        }else{
            $where=array_merge($where,['jr_club_try.lottery_status'=>0]);
            $type=0;
        }
            $model =  ClubTryUser::find()->leftJoin("jr_club_try","jr_club_try_user.try_id = jr_club_try.id")->where($where);
            $dataProvider = new ActiveDataProvider([
                'query' => $model->orderBy('creat_at desc'),
                'pagination' => [
                    'pagesize' => '8',
                ]
            ]);
        return $this->render('my-join',['dataProvider'=>$dataProvider,'model'=>$model,'type'=>$type]);
    }

}
