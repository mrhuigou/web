<?php

namespace affiliate\controllers;

use api\models\V1\AffiliateTransaction;
use api\models\V1\AffiliateTransactionFlow;
use api\models\V1\AffiliateTransactionStatement;
use api\models\V1\Customer;
use api\models\V1\OrderShipping;
use api\models\V1\OrderStatus;
use api\models\V1\ReturnBase;
use common\extensions\widgets\xlsxwriter\xlsxwriter as XLSXWriter;
use Yii;
use affiliate\models\OrderSearch;
use yii\base\ErrorException;
use yii\web\Controller;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStatement(){
        $searchModel = new OrderSearch();
        if($commission = Yii::$app->request->queryParams['OrderSearch']['commission']){
            $all_orders = $searchModel->searchAllOrder(Yii::$app->request->queryParams);
            foreach ($all_orders as &$order){
                $order->commission = bcmul($commission,$order->total,2);
                $order->save();
                $this->settleAffiliate($order,$commission);
            }

            $all_returns = $searchModel->searchAllReturn(Yii::$app->request->queryParams);
            //查询出所有 已经对完账 发送退货的订单 进行佣金回滚处理（减佣金）
            foreach ($all_returns as &$return){
                if($statement_model=AffiliateTransactionStatement::findOne(['type'=>'order','type_id'=>$return->order_id])){
                    $commission = $statement_model->commission;
                }
                $this->settleAffiliateReturn($return,$commission);
            }

            return $this->redirect(['order/index']);

        }else{
            throw new ErrorException('请输入佣金比例');
        }


    }


    private function settleAffiliateReturn($return_model,$commission){
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!$model = AffiliateTransaction::findOne(['affiliate_id' => $return_model->order->affiliate_id])) {
                $model = new AffiliateTransaction();
                $model->affiliate_id = $return_model->order->affiliate_id;
                $model->amount = 0;
            }
            $cash = '-'.bcmul($commission,$return_model->total,2);
            $model->amount = $model->amount + floatval($cash);

            if(!$flow_model=AffiliateTransactionStatement::findOne(['type'=>'return','type_id'=>$return_model->return_id])){
                if (!$model->save()) {
                    echo json_encode($model->errors);
                    throw new \Exception(json_encode($model->errors));
                }
                $flow_model = new AffiliateTransactionStatement();
                $flow_model->type="return";
                $flow_model->type_id=$return_model->return_id;
                $flow_model->affiliate_id = $model->affiliate_id;
                $flow_model->total=$return_model->total;
                $flow_model->commission=$commission;
                $flow_model->amount = floatval($cash);
                $flow_model->title = "入帐";
                $flow_model->balance = $model->amount;
                $flow_model->remark = "退货减收益";
                $flow_model->status = 1;
                $flow_model->create_at = time();
                $flow_model->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
            $transaction->rollBack();
        }
    }

    private function settleAffiliate($order_model,$commission){
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!$model = AffiliateTransaction::findOne(['affiliate_id' => $order_model->affiliate_id])) {
                $model = new AffiliateTransaction();
                $model->affiliate_id = $order_model->affiliate_id;
                $model->amount = 0;
            }
            $cash = bcmul($commission,$order_model->total,2);
            $model->amount = $model->amount + floatval($cash);

            if(!$flow_model=AffiliateTransactionStatement::findOne(['type'=>'order','type_id'=>$order_model->order_id])){
                if (!$model->save()) {
                    echo json_encode($model->errors);
                    throw new \Exception(json_encode($model->errors));
                }
                $flow_model = new AffiliateTransactionStatement();
                $flow_model->type="order";
                $flow_model->type_id=$order_model->order_id;
                $flow_model->total=$order_model->total;
                $flow_model->commission=$commission;
                $flow_model->affiliate_id = $model->affiliate_id;
                $flow_model->amount = floatval($cash);
                $flow_model->title = "入帐";
                $flow_model->balance = $model->amount;
                $flow_model->remark = "订单收益";
                $flow_model->status = 1;
                $flow_model->create_at = time();
                $flow_model->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
            $transaction->rollBack();
        }
    }

    public function actionExport(){
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pagesize'=>$dataProvider->totalCount]);
        $header = array(
            '昵称'=>'string',
            '电话'=>'string',
            '订单编号'=>'string',
            '订单时间'=>'string',
            '订单状态'=>'string',
            '订单总额'=>'string',
            '收货电话'=>'string',
//            '订单佣金'=>'string',
        );
        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );
        if($model=$dataProvider->getModels()){
            foreach($model as $value){
                $customer = Customer::findOne($value['customer_id']);
                $orderStatus = OrderStatus::findOne($value['order_status_id']);
                $orderShipping = OrderShipping::findOne(['order_id'=>$value['order_id']]);
                $writer->writeSheetRow('Sheet1',[
                    empty($customer->firstname)?'':$customer->firstname,
                    empty($customer->telephone)?'':$customer->telephone,
                    $value['order_no'],
                    $value['date_added'],
                    $orderStatus->name,
                    $value['total'],
                    $orderShipping->shipping_telephone,
//                    $value['commission']
                ]);
            }
        }

        $writer->writeToFile('/tmp/output.xlsx');

        // 输入文件标签
        header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
        header("Content-Disposition: attachment; filename=output.xlsx");  //File name extension was wrong
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        ob_clean();

        // 输出文件内容
        readfile('/tmp/output.xlsx');

    }

}
