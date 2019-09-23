<?php

namespace frontend\modules\club\controllers;

use api\models\V1\ClubCustomerSub;
use api\models\V1\ClubExperience;
use api\models\V1\ClubSubject;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TopicController extends Controller
{
    public function actionIndex()
    {
        $my_subject=ClubCustomerSub::find()->where(['customer_id'=>\Yii::$app->user->getId()])->all();
        $sub_ids=[];
        if($my_subject){
            foreach($my_subject as $my){
                $sub_ids[]=$my->sub_id;
            }
        }
        $model=ClubSubject::find()->where(['is_del'=>0])->andWhere(['not in','sub_id',$sub_ids])->all();
        return $this->render('index',['subject'=>$model,'my_subject'=>$my_subject]);
    }
    public function actionAjaxAddCustomerSubject(){
        $json = [];
        $sub_id =  (int)\Yii::$app->request->post("sub_id");
        $my_subject_count = ClubCustomerSub::find()->where(['customer_id'=>\Yii::$app->user->getId(),'sub_id'=>$sub_id])->count();
        if($my_subject_count == 0){
            $my_subject = new ClubCustomerSub();
            $my_subject->sub_id = $sub_id;
            $my_subject->customer_id = \Yii::$app->user->getId();
            $my_subject->display_order = 1;
            $my_subject->date_added = date('Y-m-d H:i:s');
            $my_subject->save();
            $json['status'] = 'success';
            $json['message'] = '话题围观成功';
        }else{
            $json['status'] = 'false';
            $json['message'] = '该话题已经添加了';
        }
        return json_encode($json);exit;
    }
    public function actionSubjectList(){
        $sub_id = \Yii::$app->request->get('sub_id');
        $query = ClubExperience::find()->joinWith('subject')->where(['sub_id'=>$sub_id]);
        $model = ClubSubject::findOne($sub_id);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy([ 'create_time' => SORT_DESC]),
            'pagination' => [
                'pagesize' => '8',
            ]
        ]);
        return $this->render('list',['dataProvider'=>$dataProvider,'model'=>$model]);
    }
    public function actionExpinfo(){
        $exp_id = \Yii::$app->request->get("id");
        if($model = ClubExperience::findOne($exp_id)){
            $model->total_click= $model->total_click + 1;
            $model->save();

            return $this->render('expinfo',['model'=>$model]);
        }else{

            throw new NotFoundHttpException('没有找到相应的活动');
        }


    }
    public function actionCancel(){
        //取消话题关注
        if(\Yii::$app->user->isGuest){
            return "请先登陆";
        }
        $sub_id = (int)\Yii::$app->request->get("sub_id");
        if($sub_id){
            $club_customer_sub = ClubCustomerSub::find()->where(['sub_id'=>$sub_id,'customer_id'=>\Yii::$app->user->getId()])->one();
            if($club_customer_sub){
                $club_customer_sub->delete();
                return $this->redirect('/club/topic/index');
            }
        }
    }
}
