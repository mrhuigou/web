<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/8/20
 * Time: 14:17
 */

namespace h5\controllers;
use api\models\V1\ClubUserComment;
use api\models\V1\Customer;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use Yii;

class ClubFeedController extends \yii\web\Controller
{

    public function actionIndex(){
        if ($customer_id = \Yii::$app->request->get("user")) {
            $model=ClubUserComment::find()->where(['type'=>['try','activity'],'status'=>1, 'customer_id'=>$customer_id])->orderBy([ 'creat_at' => SORT_DESC]);
        }else{
            $model=ClubUserComment::find()->where(['type'=>['try','activity'],'status'=>1])->orderBy([ 'creat_at' => SORT_DESC]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pagesize' => '8',
            ]
        ]);
        return $this->render('index',['dataProvider'=>$dataProvider,'model'=>$model]);
    }

    public function actionUser(){
        if (($customer_id = \Yii::$app->request->get("user")) && ($customer = Customer::findOne(\Yii::$app->request->get("user")))){
            $model=ClubUserComment::find()->where(['type'=>['try','activity'],'status'=>1, 'customer_id'=>$customer_id])->orderBy([ 'creat_at' => SORT_DESC]);
        }else{
            $this->redirect("/club-feed/index");
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pagesize' => '4',
            ]
        ]);
        return $this->render('user',['dataProvider'=>$dataProvider,'model'=>$model,'customer'=>$customer]);
    }



}