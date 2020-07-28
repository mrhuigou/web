<?php

namespace backend\controllers;

use api\models\V1\OrderMerge;
use Yii;
use api\models\V1\Order;
use backend\models\OrderSearch;
use api\models\V1\OrderPayment;
use api\models\V1\OrderShipping;
use api\models\V1\OrderProduct;
use api\models\V1\OrderDigitalProduct;
use api\models\V1\OrderHistory;
use api\models\V1\OrderStatus;
use api\models\V1\OrderTotal;
use api\models\V1\ClubActivityUser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        $searchModel = new OrderSearch();
      //  $searchModel->begin_date=date('Y-m-d',time());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }





    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        $model = $this->findModel($id);

        if (Yii::$app->request->post() && $model->order_status_id==1) {
            $discount = new OrderTotal();
            $discount->order_id = $id;
            $discount->code = 'change';
            $discount->title = Yii::$app->request->post("title");
            $discount->text = '￥'.Yii::$app->request->post("total");
            $discount->value = Yii::$app->request->post("total");
            $discount->sort_order = 3;
            $discount->save();

            $order_merage = OrderMerge::find()->where('find_in_set('.$id.',order_ids)')->andWhere(['status'=>0])->one();
            if($order_merage){
                $order_merage->status = -1;
                $order_merage->save();
            }

            $change_total = Yii::$app->request->post("total");
            $total_change = OrderTotal::findOne(['order_id'=>$id,'code'=>'total']);
            $total_change->text = '￥'.bcadd($total_change->value,Yii::$app->request->post("total"),2);
            $total_change->value = bcadd($total_change->value,Yii::$app->request->post("total"),2);
            $total_change->save();
            $model->total = bcadd($total_change->value,Yii::$app->request->post("total"),2);
            $model->save();

            $order_total_shipping=OrderTotal::findOne(['order_id'=>$id,'code'=>'shipping']);// 邮费

            if(bcadd($change_total,$order_total_shipping->value,2)<0){ // 修改金额大于了 邮费
                $order_products_models = OrderProduct::find()->where(['order_id'=>$id]);
                $pay_totals = $order_products_models->sum('pay_total');
                $order_products = $order_products_models->all();
                $change_total=bcadd($change_total,$order_total_shipping->value,2);
                $percent = bcdiv($change_total,$pay_totals,8);
                if($order_products){
                    foreach ($order_products as $order_product){
                        $product_pay_total = $order_product->pay_total;
                        $order_product->pay_total = $product_pay_total + (bcmul($product_pay_total,$percent,2));
                        $order_product->save();
                    }
                }
            }

            return $this->redirect(['view', 'id' => $id, '#'=>'tab_6_8']);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }
    public function actionChangeStatus(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if (Yii::$app->request->post() && ($model->order_status_id==12 || $model->order_status_id==13 || $model->order_status_id==7)) {
            if(in_array(Yii::$app->request->post('order_status'),[12,13,7,2])){
                $model->order_status_id = Yii::$app->request->post('order_status');
                $model->save();
                $order_history = new  OrderHistory();
                $order_history->order_id = $id;
                $order_history->order_status_id = Yii::$app->request->post('order_status');
                $order_history->comment = "订单状态更新";
                $order_history->date_added = date('Y-m-d H:i:s');
                $order_history->save();
            }

            return $this->redirect(['view', 'id' => $id, '#'=>'tab_6_6']);
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionGetOrder(){
        $order_id = Yii::$app->request->get('term');
        if($order_id){
            $order_id = intval($order_id);
            $orders = Order::find()->where(['like','order_id',$order_id.'%',false])->orderBy('order_id desc')->limit(10)->all();
            if($orders){
                $data = [];
                foreach ($orders as $order){
                    $data[] = [
                        'value' => $order->order_id,
                        'label'=>$order->order_id."---".$order->telephone,
                    ];
                }
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $data;
            }
        }
    }










}
