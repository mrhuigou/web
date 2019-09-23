<?php

namespace frontend\controllers;

use api\models\V1\CustomerChest;
//use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use common\component\image\Image;

class ChestController extends \yii\web\Controller
{
    public $layout = 'main-user';
    
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model = new CustomerChest();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->with('product')->where(['customer_id'=>\Yii::$app->user->identity->getId()]),
            'pagination' => [
                    'pagesize' => '10',
            ]
        ]);


        return $this->render('index', ['model' => $model, 'dataProvider' => $dataProvider]);
    }


}
