<?php

namespace h5\controllers;

use api\models\V1\Coupon;
use api\models\V1\Product;
use api\models\V1\RechargeCard;
use api\models\V1\RechargeHistory;
use frontend\models\RechargeCardForm;
use frontend\models\RechargeBalanceForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class AccountRechargeController extends \yii\web\Controller
{

    public function actionIndex()
    {
    	if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        if(!Yii::$app->user->identity->telephone_validate){
            return $this->redirect(['/user/security-set-telephone','redirect'=>'/account-recharge/index']);
        }
        $model = new RechargeBalanceForm();
        if ($model->load(Yii::$app->request->post()) && $order_no = $model->save()) {
            return $this->redirect(['/order/pay','order_no'=>$order_no]);
        }
        return $this->render('index',['model'=>$model]);
    }

    public function actionCard()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        if(!Yii::$app->user->identity->telephone_validate){
            return $this->redirect(['/user/security-set-telephone','redirect'=>'/account-recharge/card']);
        }
        $model = new RechargeCardForm();
        if ($model->load(Yii::$app->request->post()) && $card=$model->save()) {
            return $this->redirect(['/account-recharge/result','card_no'=>$card->card_no]);
        }
        return $this->render('card',['model'=>$model]);
    }
    public function actionResult(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        if($model=RechargeCard::findOne(['card_no'=>Yii::$app->request->get('card_no'),'status'=>1])){
	        return $this->render('result',['model'=>$model]);
        }else{
            throw new NotFoundHttpException("数据错误");
        }
    }

}
