<?php

namespace backend\controllers;


use api\models\V1\Order;
use api\models\V1\OrderHistory;
use backend\models\form\ReturnGroundPushFrom;
use Yii;
use api\models\V1\GroundPushPlan;
use backend\models\searchs\GroundPushPlanSearch;

use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroundPushPlanController implements the CRUD actions for GroundPushPlan model.
 */
class GroundPushPlanController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all GroundPushPlan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroundPushPlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GroundPushPlan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GroundPushPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GroundPushPlan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GroundPushPlan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GroundPushPlan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GroundPushPlan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GroundPushPlan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GroundPushPlan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionReturnGroundPush(){

        if(Yii::$app->request->isPost){
            $order_id = \Yii::$app->request->post('order_id');
            $product_code = \Yii::$app->request->post('product_code');
            $return_method = Yii::$app->request->post('return_method','RETURN_PAY');

            try{
                if(!$order_id){
                    throw  new  Exception("订单id不存在");
                }
                $return_ground_push_form = new ReturnGroundPushFrom($order_id,$product_code);
                $return_ground_push_form->return_method = $return_method;
                $return_ground_push_form->save();
                $data['status'] = 1;
                $data['message'] = "操作成功";
            }catch ( Exception $exception){
                $data['status'] = 0;
                $data['message'] = $exception->getMessage();
            }
            return json_encode($data);

        }else{
            $return_ground_push_form = new ReturnGroundPushFrom();
            return $this->render('_form_return-ground-push',['model'=>$return_ground_push_form]);
        }

    }
    public function actionReceive(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post("order_id");
            $order = Order::findOne(['order_id'=>$order_id,'order_type_code'=>'GroundPush']);
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if($order){
                    if($order->order_status_id == 2){
                        $order->order_status_id = 10;
                        $order->save();

                        $Order_history = new OrderHistory();
                        $Order_history->order_id = $order->order_id;
                        $Order_history->order_status_id = 10;
                        $Order_history->comment = '客户自提成功';
                        $Order_history->date_added = date('Y-m-d H:i:s', time());
                        if (!$Order_history->save(false)) {
                            throw new \Exception("订单记录异常");
                        }
                        $transaction->commit();

                        $result['status'] = true;
                        $result['message'] = '收货成功';

                    }else{
                        throw new Exception("未支付或者已经领取了");
                    }
                }else{
                    throw new Exception("订单不存在，请确认输入");
                }
            } catch (Exception $e) {
                $result['status'] = false;
                $result['message'] = '数据更新错误====>'.$e->getMessage();
                $transaction->rollBack();
                //throw new NotFoundHttpException($e->getMessage());
            }
        }else{
            return $this->render('_form_receive');
        }

        return json_encode($result);

    }

}
