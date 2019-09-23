<?php

namespace frontend\controllers;

use api\models\V1\OrderBlack;
use api\models\V1\OrderGift;
use api\models\V1\OrderMerge;
use api\models\V1\PromotionHistory;
use api\models\V1\Review;
use api\models\V1\WarehouseLog;
use api\models\V1\WarehouseStock;
use common\component\Curl\Curl;
use common\component\Helper\OrderSn;
use Yii;
use api\models\V1\CouponHistory;
use api\models\V1\CustomerChest;
use api\models\V1\CustomerCoupon;
use api\models\V1\Order;
use api\models\V1\OrderProduct;
use api\models\V1\OrderTotal;
use api\models\V1\PromotionDetailGift;
use api\models\V1\ReturnBase;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use frontend\models\ReturnForm;
use frontend\models\ReviewForm;

class OrderController extends \yii\web\Controller
{
	public $layout = 'main-user';
    
    public function actionIndex()
    {
    	if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
    	$model = Order::find()->where(['customer_id'=>\Yii::$app->user->getId()]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->orderBy('date_added DESC'),
            'pagination' => [
                'pagesize' => '10',
            ]
        ]);
        return $this->render('index',['dataProvider'=>$dataProvider,'model'=>$model]);
    }

    public function actionInfo(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $order_no = \Yii::$app->request->get("order_no");
        $model = Order::findOne(['order_no'=>$order_no,'customer_id'=> \Yii::$app->user->getId()]);
        if($model) {
            return $this->render('info', ['model' => $model]);
        }else{
            throw new NotFoundHttpException("没有找到相应页面");
        }
    }
    public function actionShipping(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $order_no = \Yii::$app->request->get("order_no");
        if($model = Order::findOne(['order_no'=>$order_no,'customer_id'=> \Yii::$app->user->getId()])){
            $curl=new Curl();
            $pass_path=$curl->get(\Yii::$app->params['TMS_API'].'/jiarunTmsServer/getCoorByOrderCode.action',['orderCode'=>'O'.$model->order_id]);
            $order_data=$curl->get(\Yii::$app->params['TMS_API'].'/jiarunTmsServer/getLocationsByOrderCode.action',['orderCode'=>'O'.$model->order_id]);
            if(($data=Json::decode($order_data)) && $data['success']==false){
                $data=[];
            }
            return $this->render('shipping', ['model' => $model,'order_path'=>$data,'pass_path'=>Json::decode($pass_path)]);
        }else{
            throw new NotFoundHttpException("没有找到相应页面");
        }
    }
     public function actionCancel(){

        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }

        $order_no = \Yii::$app->request->get("order_no");
        if($model = Order::findOne(['order_no'=>$order_no,'customer_id'=> \Yii::$app->user->getId()])){
            $this->returnOrderOccupation($model->order_id);
            $model->order_status_id=7;
            $model->save();
            return $this->redirect(['/order/info','order_no'=>$order_no]);
        }else{
            throw new NotFoundHttpException("没有找到相应页面");
        }
    }
    public function actionAddCart(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $order_no = \Yii::$app->request->get("order_no");
        if($model = Order::findOne(['order_no'=>$order_no,'customer_id'=> \Yii::$app->user->getId(),'order_type_code'=>['normal','presell']])){
            if($model->orderProducts){
                foreach($model->orderProducts as $order_product){
                    if($order_product->product){
                        \Yii::$app->cart->put($order_product->product->getCartPosition(), $order_product->quantity);
                    }
                }
            }else{
                throw new NotFoundHttpException("没有找到相应商品");
            }
            return $this->redirect('/cart/index');
        }else{
            throw new NotFoundHttpException("没有找到相应订单商品");
        }
    }
    public function actionRefund(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $order_no = \Yii::$app->request->get("order_no");
        $item_id=\Yii::$app->request->get("item_id");
        if(!$order = Order::findOne(['order_no'=>$order_no,'customer_id'=>\Yii::$app->user->getId(),'order_status_id'=>[2,3,5,9,10,13]])){
            throw new NotFoundHttpException("没有找到相应订单");
        }
        if(!$order_product=OrderProduct::findOne(['order_product_id'=>$item_id,'order_id'=>$order->order_id])){
            throw new NotFoundHttpException("没有找到相应订单商品");
        }
        if($order_product->quantity<=$order_product->getRefundQty()){
            return $this->redirect('/order/return');
        }

        $model = new ReturnForm(['order'=>$order,'order_product'=>$order_product]);
        if ($model->load(\Yii::$app->request->post()) && $model->submit()) {
            return $this->redirect('/order/return');
        }

        return $this->render('refund',['model'=>$model,'order'=>$order,'order_product'=>$order_product]);
    }
    public function actionReturn(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model = new ReturnBase();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->where(['customer_id'=>\Yii::$app->user->getId()])->orderBy('date_added DESC'),
            'pagination' => [
                'pagesize' => '10',
            ]
        ]);
        return $this->render('return',['dataProvider'=>$dataProvider,'model'=>$model]);

    }
    public function actionReview(){

        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Review::find()->where(['customer_id'=>\Yii::$app->user->getId()])->orderBy('date_added DESC'),
            'pagination' => [
                'pagesize' => '10',
            ]
        ]);
        return $this->render('review',['dataProvider'=>$dataProvider]);
    }
    public function actionReviewForm(){

        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $order_no = \Yii::$app->request->get("order_no");
        $item_id=\Yii::$app->request->get("item_id");
        if(!$order = Order::findOne(['order_no'=>$order_no,'customer_id'=>\Yii::$app->user->getId()])){
            throw new NotFoundHttpException("没有找到相应订单");
        }
        if(!$order_product=OrderProduct::findOne(['order_product_id'=>$item_id,'order_id'=>$order->order_id])){
            throw new NotFoundHttpException("没有找到相应订单商品");
        }
        if($order_product->review){
            return $this->redirect('/order/review');
        }
        $model = new ReviewForm(['order'=>$order,'order_product'=>$order_product]);
        if ($model->load(\Yii::$app->request->post()) && $model->submit()) {
            return $this->redirect('/order/review');
        }
        return $this->render('review-form',['model'=>$model,'order'=>$order,'order_product'=>$order_product]);
    }


    protected function returnOrderOccupation($order_id){
         //返还宝物
         $this->returnOrderChest($order_id);
         //返还优惠券
         $order_totals = OrderTotal::find()->where(['code'=>'coupon','order_id'=>$order_id])->all();
         if($order_totals){
             foreach($order_totals as $order_total){
                 if($customer_coupon = CustomerCoupon::findOne(['customer_coupon_id'=> $order_total->customer_code_id,'is_use'=>1])){
                     $customer_coupon->is_use = 0;
                     $customer_coupon->date_used = '';
                     $customer_coupon->save();
                 }
             }
             CouponHistory::deleteAll(['order_id'=>$order_id]);
         }
         PromotionHistory::updateAll(['status'=>0],['order_id'=>$order_id]);
         OrderBlack::deleteAll(['order_id'=>$order_id]);
         if($model=Order::findOne(['order_id'=>$order_id])){
             if($model->orderProducts){
                 foreach($model->orderProducts as $order_product){
                     if($m=WarehouseStock::findOne(['product_code'=>$order_product->product_code])){
                         $m->tmp_qty= max(0,$m->tmp_qty - $order_product->quantity);
                         $m->save();
//	                     $log=new WarehouseLog();
//	                     $log->type='cancel_order_product_pc';
//	                     $log->product_code=$m->product_code;
//	                     $log->qty=intval($m->quantity);
//	                     $log->create_time=time();
//	                     $log->save();
                     }
                     if($order_product->gift){
                         foreach($order_product->gift as $order_product_gift){
                             if($m=WarehouseStock::findOne(['product_code'=>$order_product_gift->product_code])){
                                 $m->tmp_qty= max(0,$m->tmp_qty - $order_product_gift->quantity);
                                 $m->save();
//	                             $log=new WarehouseLog();
//	                             $log->type='cancel_order_product_gift_pc';
//	                             $log->product_code=$m->product_code;
//	                             $log->qty=intval($m->quantity);
//	                             $log->create_time=time();
//	                             $log->save();
                             }
                         }
                     }
                 }
             }
             if($model->orderGifts){
                 foreach($model->orderGifts as $order_product){
                     if($m=WarehouseStock::findOne(['product_code'=>$order_product->product_code])){
                         $m->tmp_qty= max(0,$m->tmp_qty - $order_product->quantity);
                         $m->save();
//	                     $log=new WarehouseLog();
//	                     $log->type='cancel_order_gift_pc';
//	                     $log->product_code=$m->product_code;
//	                     $log->qty=intval($m->quantity);
//	                     $log->create_time=time();
//	                     $log->save();
                     }
                 }
             }
         }
         return true;
     }
     public function returnOrderChest($order_id){
         $chests = CustomerChest::find()->where(['customer_id'=> \Yii::$app->user->getId(),'order_id'=>$order_id,'status'=>1])->all();
         if($chests) {
             foreach ($chests as $ch) {
                 $ch->status = 0;
                 $ch->order_id = 0;
                 $ch->order_no = '';
                 $ch->save();
             }
         }
         return true;

     }
    public function actionPay(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
        }
        $order_no=\Yii::$app->request->get('order_no');
        if($model=Order::findOne(['order_no'=>$order_no,'order_status_id'=>1,'customer_id'=>\Yii::$app->user->getId()])){
            if(!$tradeModel=OrderMerge::findOne(['order_ids'=>$model->order_id,'status'=>0])){
                $tradeModel=new OrderMerge();
                $tradeModel->merge_code=OrderSn::generateNumber();
                $tradeModel->order_ids=$model->order_id;
                $tradeModel->total=$model->total;
                $tradeModel->customer_id=\Yii::$app->user->getId();
                $tradeModel->status=0;
                $tradeModel->date_added=date("Y-m-d H:i:s");
                $tradeModel->date_modified=date("Y-m-d H:i:s");
                $tradeModel->save();
            }
            return $this->redirect(Url::to(['/payment/index','trade_no'=>$tradeModel->merge_code,'showwxpaytitle'=>1],true));
        }else{
            throw new NotFoundHttpException("订单已过期！");
        }
    }
}
