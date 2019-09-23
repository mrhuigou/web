<?php

namespace frontend\controllers;

use api\models\V1\Coupon;
use api\models\V1\CouponProduct;
use frontend\models\CouponCardForm;
use Yii;
use api\models\V1\CustomerCoupon;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class UserCouponController extends \yii\web\Controller
{
    public $layout = 'main-user';

    public function actionIndex()
    {
    	if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }

        $model = CustomerCoupon::find()->joinWith(['coupon' => function ($query) {
            $query->andFilterWhere([">=", "jr_coupon.status", 1]);
        }])->where(['customer_id' => \Yii::$app->user->identity->getId(), 'is_use' => 0])
            ->andWhere([">=", "end_time", date('Y-m-d H:i:s', time())]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->orderBy('customer_coupon_id desc'),
            'pagination' => [
                'pagesize' => '12',
            ]
        ]);
        return $this->render('index',['dataProvider'=>$dataProvider,'model'=>$model]);
    }
    public function actionCard(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model=new CouponCardForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }
        return $this->render('card',['model'=>$model]);
    }
    public function actionDetail(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $id=Yii::$app->request->get('id');
        if($coupon=CustomerCoupon::findOne(['customer_id'=>Yii::$app->user->getId(),'customer_coupon_id'=>$id])){
            $model=CouponProduct::find()->where(['coupon_id'=>$coupon->coupon_id,'status'=>1]);
            $dataProvider = new ActiveDataProvider([
                'query' => $model->orderBy('coupon_product_id desc'),
                'pagination' => [
                    'pagesize' => '15',
                ]
            ]);
            return $this->render('detail', ['coupon'=>$coupon,'model' => $model,'dataProvider' => $dataProvider]);
        }else{
            throw new NotFoundHttpException('没有找到相关页面');
        }
    }
}
