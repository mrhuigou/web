<?php
namespace frontend\modules\club\controllers;
use api\models\V1\Address;
use api\models\V1\ClubSubject;
use api\models\V1\ClubTry;
use api\models\V1\ClubTryUser;
use api\models\V1\ClubUserInvite;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TryController extends Controller
{
    public function actionIndex()
    {
        $status=1;
        if(\Yii::$app->request->get('status')==2){
            $status=2;
        }
        if(\Yii::$app->request->get('status')==3){
            $status=3;
        }
        $model=ClubTry::find();
        if($status==1){
            $model->where(['and','begin_datetime<=now()','end_datetime>=now()']);
        }elseif($status==2){
            $model->where('begin_datetime>now()');
        }else{
            $model->where('end_datetime<now()');
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $model->orderBy([ 'sort_order' => SORT_ASC]),
            'pagination' => [
                'pagesize' => '10',
            ]
        ]);
        return $this->render('index',['model'=>$model,'dataProvider'=>$dataProvider,'status'=>$status]);
    }
    public function actionInfo(){
        if($model=ClubTry::findOne(['id'=>\Yii::$app->request->get('id')])){
            $model->click_count= $model->click_count+1;
            $model->save();
            $UserInvite="";
            if(!\Yii::$app->user->isGuest){
                if(!$UserInvite=ClubUserInvite::findOne(['customer_id'=>\Yii::$app->user->getId(),'type'=>'try','type_id'=>$model->id])){
                    $UserInvite=new ClubUserInvite();
                    $UserInvite->customer_id=\Yii::$app->user->getId();
                    $UserInvite->type='try';
                    $UserInvite->type_id=$model->id;
                    $UserInvite->code=strval(time().rand(10000,999999));
                    $UserInvite->creat_at=date('Y-m-d H:i:s',time());
                    $UserInvite->save();
                }
            }
            $TryUsers=$this->runAction('try-user',['id'=>$model->id]);
            $TryUsersGet=$this->runAction('try-user-get',['id'=>$model->id]);
            return $this->render('info',['model'=>$model,'try_users'=>$TryUsers,'try_users_get'=>$TryUsersGet,'user_invite'=>$UserInvite]);
        }else{
            throw new NotFoundHttpException('没有找到相应的活动');
        }
    }
    public function actionTryUser(){
        $model=ClubTryUser::find()->where(['try_id'=>\Yii::$app->request->get('id')]);
        $pages = new Pagination(['totalCount' =>$model->count(), 'pageSize' => '6']);
        $model = $model->offset($pages->offset)->limit($pages->limit)->all();
        return $this->renderPartial('try-user',['model'=>$model,'pages' => $pages]);
    }
    public function actionTryUserGet(){
        $model=ClubTryUser::find()->where(['try_id'=>\Yii::$app->request->get('id'),'status'=>1]);
        $pages = new Pagination(['totalCount' =>$model->count(), 'pageSize' => '6']);
        $model = $model->offset($pages->offset)->limit($pages->limit)->all();
        return $this->renderPartial('try-user-get',['model'=>$model,'pages' => $pages]);
    }
    public function actionGetAddress(){
        $json=[];
        if(\Yii::$app->request->isAjax && \Yii::$app->request->isGet){
            if(\Yii::$app->user->isGuest){
                $json=['status'=>0,'message'=>'您还没有登陆，请先登陆！'];
            }else{
                $model=Address::find()->where(['customer_id'=>\Yii::$app->user->getId()])->all();
                $data=$this->renderPartial('address',['model'=>$model]);
                $json=['status'=>1,'data'=>$data];
            }
        }else{
            $json=['status'=>0,'message'=>'请求错误'];
        }
        return Json::encode($json);
    }
    public function actionSubmit(){
        $json=[];
        if(\Yii::$app->request->isAjax && \Yii::$app->request->isPost){
            if($address=Address::findOne(['customer_id'=>\Yii::$app->user->getId(),'address_id'=>\Yii::$app->request->post('address_id')])){
                if(!$model=ClubTryUser::findOne(['try_id'=>\Yii::$app->request->post('id'),'customer_id'=>\Yii::$app->user->getId()])){
                    if($try=ClubTry::findOne(['id'=>\Yii::$app->request->post('id')])){
                        if(strtotime($try->begin_datetime)<=time() && strtotime($try->end_datetime)>=time()){
                            $model=new ClubTryUser();
                            $model->try_id=$try->id;
                            $model->customer_id=\Yii::$app->user->getId();
                            $model->shipping_name=$address->firstname;
                            $model->shipping_telephone=$address->telephone;
                            $model->zone_id=$address->zone_id;
                            $model->city_id=$address->city_id;
                            $model->district_id=$address->district_id;
                            $model->address=$address->address_1;
                            $model->postcode=$address->postcode;
                            $model->status=0;
                            $model->creat_at=date('Y-m-d H:i:s',time());
                            $model->save();
                            $json=['status'=>1,'message'=>'报名成功'];
                        }else{
                            $json=['status'=>0,'message'=>'报名已经结束'];
                        }
                    }else{
                        $json=['status'=>0,'message'=>'活动不存在'];
                    }
                }else{
                    $json=['status'=>0,'message'=>'您已经报过名了'];
                }
            }else{
                $json=['status'=>0,'message'=>'地址不存在'];
            }
        }else{
            $json=['status'=>0,'message'=>'请求错误'];
        }
        return Json::encode($json);
    }

}
