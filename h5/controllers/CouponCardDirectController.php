<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/11
 * Time: 9:52
 */
namespace h5\controllers;
use api\models\V1\Address;
use api\models\V1\ExpressCardProduct;
use api\models\V1\ExpressCardView;
use api\models\V1\ExpressCardViewLog;
use api\models\V1\ExpressOrder;
use api\models\V1\ExpressOrderProduct;
use common\component\Helper\OrderSn;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class CouponCardDirectController extends \yii\web\Controller {
    public $layout="main_other";
    public function actionIndex()
    {
        if (!$url = \Yii::$app->request->get('redirect')) {
            $url = "/express/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => $url]);
        }
        $model=ExpressOrder::find()->where(['customer_id'=>\Yii::$app->user->getId()])->orderBy('id desc')->limit(5)->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionOrder(){
        if (!$url = \Yii::$app->request->get('redirect')) {
            $url = "/express/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => $url]);
        }
        $model =ExpressOrder::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->where(['customer_id'=>\Yii::$app->user->getId()])->orderBy('id DESC'),
            'pagination' => [
                'pagesize' => '4',
            ]
        ]);
        return $this->render('order',['model'=>$model,'dataProvider'=>$dataProvider]);
    }

    public function actionOrderInfo(){
        if (!$url = \Yii::$app->request->get('redirect')) {
            $url = \Yii::$app->request->getAbsoluteUrl();
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => $url]);
        }
        $order_code=\Yii::$app->request->get('order_no');
        if($model=ExpressOrder::findOne(['order_code'=>$order_code,'customer_id'=>\Yii::$app->user->getId()])){
            return $this->render('order-info',['model'=>$model]);
        }else{
            throw  new NotFoundHttpException('找不到当前信息');
        }

    }


    public function actionCheckOrder()
    {
        if (!$url = \Yii::$app->request->get('redirect')) {
            $url = "/express/check-order";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => $url]);
        }
        if (!$card_id = \Yii::$app->session->get('ExpressCardViewId')) {
            return $this->redirect('index');
        }
        if($card_model = ExpressCardView::findOne(['id' =>$card_id])){
            $model=ExpressCardProduct::find()->where(['express_card_id'=>$card_model->express_card_id,'status'=>1])->all();
            return $this->render('check-order',['model'=>$model]);
        }else{
            return $this->redirect('index');
        }
    }
    public function actionSaveOrder(){
        try{
            if (!$address_id = \Yii::$app->request->post('address_id')) {
                throw new ErrorException('收货地址不能为空');
            }
            if(!$address=Address::findOne(['address_id'=>$address_id,['customer_id'=>\Yii::$app->user->getId()]])){
                throw new ErrorException('收货地址不存在');
            }
            if (!$card_id = \Yii::$app->session->get('ExpressCardViewId')) {
                throw new ErrorException('提货券过期');
            }
            if ($model = ExpressCardView::findOne(['id' =>$card_id])) {
                if (!$model->card->status) {
                    throw new ErrorException('此卡已经失效');
                }
                if ($model->status) {
                    throw new ErrorException('此卡号已经使用过了');
                }
                if(!$card_product=ExpressCardProduct::find()->where(['express_card_id'=>$model->express_card_id,'status'=>1])->all()){
                    throw new ErrorException('提货券没有绑定任何商品，无法使用');
                }
            } else {
                throw new ErrorException('提货券不存在');
            }

            $delivery_date=\Yii::$app->request->post('delivery_date');
            $delivery_time=\Yii::$app->request->post('delivery_time');
            $remark=\Yii::$app->request->post('remark');
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $order_model=new ExpressOrder();
                $order_model->company_id=$model->card->legal_person_id;
                $order_model->customer_id=\Yii::$app->user->getId();
                $order_model->order_type='EXPRESS';
                $order_model->order_code=OrderSn::generateNumber();
                $order_model->contact_name=$address->firstname;
                $order_model->telephone=$address->telephone;
                $order_model->city=$address->citys->name;
                $order_model->district=$address->district->name;
                $order_model->address=$address->address_1;
                if($delivery_date && $delivery_time){
                    $order_model->delivery_type='LIMIT';
                    $order_model->delivery_date=$delivery_date;
                    $order_model->delivery_time=$delivery_time;
                }else{
                    $order_model->delivery_type='ANY';
                    $order_model->delivery_date=$delivery_date;
                    $order_model->delivery_time=$delivery_time;
                }
                $order_model->total=0;
                $order_model->remark=$remark;
                $order_model->express_status_id=1;
                $order_model->send_status=0;
                $order_model->create_at=time();
                if(!$order_model->save(false)){
                    throw new ErrorException('订单错误');
                }
                foreach ($card_product as $product){
                    $order_product_model=new ExpressOrderProduct();
                    $order_product_model->order_id=$order_model->id;
                    $order_product_model->shop_code=$product->shop_code;
                    $order_product_model->product_base_code=$product->product_base_code;
                    $order_product_model->product_code=$product->product_code;
                    $order_product_model->product_name=$product->product_name;
                    $order_product_model->quantity=$product->quantity;
                    $order_product_model->description=$product->description;
                    if(!$order_product_model->save(false)){
                        throw new ErrorException('订单商品错误');
                    }
                }
                $fn = function ($card_id) use (&$fn){
                    $express_card_view = ExpressCardView::findOne(['id'=>$card_id]);
                    if($express_card_view->status){
                        throw new Exception("卡券已经使用过了");
                    }
                    try{
                        $express_card_view->status=1;
                        $express_card_view->save(false);
                    }catch (StaleObjectException $e){
                        //重新验证下状态
                        $fn($card_id);
                    }
                    $card_log=new ExpressCardViewLog();
                    $card_log->customer_id=\Yii::$app->user->getId();
                    $card_log->express_card_view_id=$express_card_view->id;
                    $card_log->user_agent=\Yii::$app->request->getUserAgent();
                    $card_log->ip=\Yii::$app->request->getUserIP();
                    $card_log->create_at=time();
                    $card_log->save(false);
                };
                $fn($card_id);
                $transaction->commit();
                \Yii::$app->session->remove("checkout_express_address_id");
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new ErrorException($e->getMessage());
            }
            $data = ['status' => 1, 'location' => '/express/index'];
        } catch (ErrorException $e) {
            $data = ['status' => 0, 'message' => $e->getMessage()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
    public function actionSelectAddress()
    {
        try{
            if(\Yii::$app->request->isAjax && \Yii::$app->request->isPost) {
                if ($address_id = \Yii::$app->request->post('address_id')) {
                    $default_address = Address::findOne(['customer_id' => \Yii::$app->user->getId(), 'address_id' => $address_id]);
                    if ($default_address) {
                        \Yii::$app->session->remove('checkout_express_address_id');
                        \Yii::$app->session->set('checkout_express_address_id', $address_id);
                        $data = ['result' => true, 'html' => $this->renderAjax('select-address',['model'=>$default_address])];
                    } else {
                        throw new ErrorException('数据加载失败');
                    }
                }
            }else{
                throw new ErrorException('数据加载失败');
            }
        } catch (ErrorException $e) {
            $data = ['result' => false, 'message' => $e->getMessage()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


}