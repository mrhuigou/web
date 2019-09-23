<?php
namespace h5\controllers;
use api\models\V1\CustomerBehavior;
use yii\data\ActiveDataProvider;

class FootPrintController extends \yii\web\Controller {

    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model = new CustomerBehavior();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->where(['customer_id' => \Yii::$app->user->getId(), 'type' =>'view'])->groupBy('item_id')->orderBy('datetime DESC'),
            'pagination' => [
                'pagesize' => '10',
            ]
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider, 'model' => $model]);
    }
}
