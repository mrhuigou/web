<?php

namespace h5\controllers;

use api\models\V1\Message;
use api\models\V1\NewsLog;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class MessageController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = "/message/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
        $model= new Message();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->where(['customer_id'=>\Yii::$app->user->getId()])->andWhere(['OR','device = "ALL"','device= "H5"'])->orderBy([ 'message_id' => SORT_DESC]),
            'pagination' => [
                'pagesize' => '4',
            ]
        ]);
        return $this->render('index',['dataProvider'=>$dataProvider,'model'=>$model]);
    }
    public function actionInfo(){
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = "/message/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
        if($model=Message::findOne(['message_id'=>\Yii::$app->request->get('message_id')])){
            if(!$model){
                if($model->is_read == 0){
                    $model->is_read = 1;
                    $model->save();
                }
            }
            return $this->render('info',['model'=>$model]);
        }else{
            throw new NotFoundHttpException("查不到当前消息！");
        }
    }
}