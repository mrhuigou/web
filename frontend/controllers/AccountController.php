<?php

namespace frontend\controllers;
use api\models\V1\Customer;
use api\models\V1\CustomerBehavior;
use api\models\V1\CustomerCollect;
use api\models\V1\CustomerFootprint;
use common\component\Helper\Helper;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class AccountController extends \yii\web\Controller
{
	public $layout = 'main-user';
    
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }

        $model=Customer::findOne(Yii::$app->user->identity->getId());
        $footprint = CustomerCollect::find()->with('product')->where(['customer_id'=>\Yii::$app->user->getId()])->andwhere('product_id <> 0')->orderBy('RAND()')->limit(6)->all();
        return $this->render('index',['model'=>$model,'footprint'=>$footprint]);
    }

    public function actionMyfav()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $collect = CustomerBehavior::find()->where(['customer_id'=>\Yii::$app->user->getId()])->groupBy('item_id')->orderBy(['datetime'=>SORT_DESC])->limit(6)->all();
        return $this->renderPartial('myfav',['model'=>$collect]);

    }

}
