<?php

namespace affiliate\controllers;

use api\models\V1\AffiliateTransaction;
use api\models\V1\AffiliateTransactionFlow;
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

    public function actionCommission(){
        $searchModel = new OrderSearch();
        if($commission = Yii::$app->request->queryParams['OrderSearch']['commission']){
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->setPagination(['pagesize'=>$dataProvider->totalCount]);
            if($model=$dataProvider->getModels()){
                foreach($model as $order){
                    $order->commission = bcmul($commission,$order->total,2);
                    $order->save();

                    //生成对应订单的 收益
                    //检测是否发生退货处理
                    $cash =  $order->commission ;
                    $returns = ReturnBase::find()->where(['order_id'=>$order->order_id])->andWhere(['<>','return_status_id','6'])->all();
                    if($returns){
                        foreach ($returns as $return){
                            $cash = $cash - bcmul($return->total,$commission,2);
                        }
                    }
                    if($cash < 0){
                        $cash = 0;
                    }

                    $this->settleAffiliate($order,$cash);
                }
            }

            return $this->redirect(['order/index']);

        }else{
            throw new ErrorException('请输入佣金比例');
        }


    }


    private function settleAffiliate($order_model,$cash){
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!$model = AffiliateTransaction::findOne(['affiliate_id' => $order_model->affiliate_id])) {
                $model = new AffiliateTransaction();
                $model->affiliate_id = $order_model->affiliate_id;
                $model->amount = 0;
            }
            $model->amount = $model->amount + floatval($cash);

            if(!$flow_model=AffiliateTransactionFlow::findOne(['type'=>'order','type_id'=>$order_model->order_id])){
                if (!$model->save()) {
                    echo json_encode($model->errors);
                    throw new \Exception(json_encode($model->errors));
                }
                $flow_model = new AffiliateTransactionFlow();
                $flow_model->type="order";
                $flow_model->type_id=$order_model->order_id;
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
            '订单编号'=>'string',
            '订单总额'=>'string',
            '订单状态'=>'string',
            '订单佣金'=>'string',
            '创建时间'=>'string',
        );
        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );
        if($model=$dataProvider->getModels()){
            foreach($model as $value){
                $writer->writeSheetRow('Sheet1',[
                    $value['order_no'],$value['total'],
                    $value['order_status_id'],
                    $value['commission'],
                    $value['date_added']
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
