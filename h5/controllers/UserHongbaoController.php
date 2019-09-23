<?php

namespace h5\controllers;
use api\models\V1\CustomerHongbao;
use api\models\V1\CustomerHongbaoHistroy;
use yii\data\ActiveDataProvider;
use Yii;

class UserHongbaoController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        if(Yii::$app->request->get('status')){
            $model=CustomerHongbaoHistroy::find()->where(['customer_id'=>Yii::$app->user->getId()])->orderBy(' id desc ');
            $dataProvider = new ActiveDataProvider([
                'query' => $model,
                'pagination' => [
                    'pagesize' => '4',
                ]
            ]);
            return $this->render('other',['dataProvider'=>$dataProvider,'model'=>$model]);
        }else{
            $model=CustomerHongbao::find()->where(['customer_id'=>Yii::$app->user->getId()])->andWhere(['>','create_at',strtotime("-1 week")])->orderBy(' id desc ');
            $dataProvider = new ActiveDataProvider([
                'query' => $model,
                'pagination' => [
                    'pagesize' => '4',
                ]
            ]);
            return $this->render('index',['dataProvider'=>$dataProvider,'model'=>$model]);
        }
    }
}
