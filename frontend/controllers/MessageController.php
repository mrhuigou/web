<?php

namespace frontend\controllers;

use api\models\V1\Message;
use yii\data\ActiveDataProvider;

class MessageController extends \yii\web\Controller
{
    public $layout = 'main-user';

    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model = new Message();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->where(['OR','device = "ALL"','device= "PC"'])->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])->orderBy('date_added DESC'),
            'pagination' => [
                'pagesize' => '10',
            ]
        ]);
        $count_unread = $this->getMessageCount(0);
        $count_read = $this->getMessageCount(1);
        return $this->render('index', ['model' => $model, 'dataProvider' => $dataProvider,'count_unread'=>$count_unread,'count_read'=>$count_read]);
    }
    public function actionInfo(){
        $message_id = \Yii::$app->request->get("message_id");
        $model = "";
        if(!empty($message_id)){
            $model = Message::findOne($message_id);
            if(!$model->is_read){
                $model->is_read = 1;
                $model->save();
            }
        }
        return $this->render('info', ['model' => $model]);
    }
    private function getMessageCount($is_read = 0){
        if(!\Yii::$app->user->isGuest){
            $count = Message::find()->where(['customer_id'=> \Yii::$app->user->getId(),'is_read'=>$is_read])->count();
        }else{
            $count = 0;
        }
        return $count;
    }
    public function actionSetRead(){
        $json = array();
        $message_id = \Yii::$app->request->post("message_id");
        if($message_id){
            $message = Message::findOne($message_id);
            if($message){
                if($message->is_read == 0){
                    $message->is_read = 1;
                    $message->save();
                }
            }
            $json['status'] = 'success';
        }else{
            $json['status'] = 'false';
            $json['msg'] = '错误的请求';
        }

        return json_encode($json);exit;

    }

}
