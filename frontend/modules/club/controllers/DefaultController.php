<?php

namespace frontend\modules\club\controllers;

use api\models\V1\ClubActivity;
use api\models\V1\ClubActivityCategory;
use api\models\V1\ClubActivityItems;
use api\models\V1\ClubActivityUser;
use api\models\V1\OrderActivity;
use frontend\modules\club\models\ActivityOrderForm;
use frontend\modules\club\models\ActivitySearch;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{

    public function actionIndex()
    {
        if(!$filter=\Yii::$app->request->get('filter')){
            $filter=[];
        }
        $cate_model=ClubActivityCategory::find()->all();
        $model = new ActivitySearch();
        $dataProvider = $model->search(['ActivitySearch'=>$filter]);
        return $this->render('index',['category'=>$cate_model,'filter'=>$filter,'model'=>$model,'dataProvider'=>$dataProvider]);
    }
    public function actionInfo(){
        if($model=ClubActivity::findOne(['id'=>\Yii::$app->request->get('id')])){
            $model->click_count= $model->click_count+1;
            $model->save();
            $ActivityUsers=$this->runAction('activity-user',['id'=>$model->id]);
            return $this->render('info',['model'=>$model,'activity_users'=>$ActivityUsers]);
        }else{
            throw new NotFoundHttpException('没有找到相应的活动');
        }
    }
    public function actionActivityUser(){
        $model=OrderActivity::find()->joinWith(['order'=>function($query){ $query->andWhere(['not in','order_status_id',['1','7']]);}]);
        $model=$model->where(['activity_id'=>\Yii::$app->request->get('id')]);
        $pages = new Pagination(['totalCount' =>$model->count(), 'pageSize' => '6']);
        $model = $model->offset($pages->offset)->limit($pages->limit)->all();
        return $this->renderPartial('activity-user',['model'=>$model,'pages' => $pages]);
    }
    public function actionApply(){
        if(\Yii::$app->request->isAjax && \Yii::$app->request->isPost){
            if(\Yii::$app->user->isGuest){
                $json=['status'=>0,'message'=>'您还没有登陆，请先登陆！'];
            }else{
                $model=ClubActivity::findOne(['id'=>\Yii::$app->request->post('id')]);
                $data=$this->renderPartial('activity-items',['model'=>$model]);
                $json=['status'=>1,'data'=>$data];
            }
        }else{
            $json=['status'=>0,'message'=>'请求错误'];
        }
        return Json::encode($json);
    }
    public function actionSubmit(){
        if(\Yii::$app->request->isAjax && \Yii::$app->request->isPost){
            if(\Yii::$app->user->isGuest){
                $json=['status'=>0,'message'=>'您还没有登陆，请先登陆！'];
            }else{
                $model=new ActivityOrderForm();
                if($model->load(['ActivityOrderForm'=>\Yii::$app->request->post()]) && $order=$model->save()){
                    $json=['status'=>1,'redirect'=>Url::to(['/payment/index','trade_no'=>$order->order_id],true)];
                }else{
                    $json=['status'=>0,'message'=>$model->firstErrors];
                }
            }
        }else{
            $json=['status'=>0,'message'=>'请求错误'];
        }
        return Json::encode($json);
    }

}
