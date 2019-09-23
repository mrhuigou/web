<?php
namespace api\controllers\payment;
use api\models\V1\CheckoutOrder;
use api\models\V1\Order;
use api\models\V1\OrderMerge;
use common\component\Payment\Bestone\BestoneSubmit;
use yii\base\Exception;


class BestoneController extends \yii\web\Controller
{
    public function actionIndex(){
        try {
            //计算得出通知验证结果
            $params = \Yii::$app->request->post();
            $bestOne = new BestoneSubmit();

            //验证成功
            if($bestOne->verifySign($params)) {
                //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
                //商户订单号
                $out_trade_no= \Yii::$app->request->post('orderno');
                //支付宝交易号
                $trade_no = \Yii::$app->request->post('trade_no');
                //交易状态
                $trade_status =\Yii::$app->request->post('trade_status');

                if(intval($trade_status) == 1) {
                    $model = OrderMerge::findOne(['merge_code' => $out_trade_no, 'status' => 0]);
                    if($model){
                        $order_ids = explode(",", $model->order_ids);
                        foreach ($order_ids as $order_id) {
                            if ($order = Order::findOne(['order_id' => $order_id])) {
                                $order->invoice_title = "";
                                $order->invoice_temp = "不需要发票";
                                $order->save();
                            }
                        }
                    }else{
                        throw new \Exception("订单信息不存在,merge_code".$out_trade_no);
                    }

                    //支付流水号
                    $transaction_id=$trade_no;
                    $model=new CheckoutOrder();
                    $model->out_trade_no=$out_trade_no;
                    $model->transaction_id=$transaction_id;
                    $model->payment_method='佰通卡支付';
                    $model->payment_code='precardPay';
                    $model->remak=serialize(\Yii::$app->request->post());
                    $model->staus=2;
                    $model->save();
                }
                echo "success";		//请不要修改或删除
            }else {
                //验证失败
                echo "fail";
                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
        }
        catch(\Exception $exp)
        {
            \Yii::error("【业务出错】:\n".$exp->getMessage()."\n" .$exp->getFile()."\n".$exp->getLine()."\n".serialize(\Yii::$app->request->post())."\n");
            return false;
        }
    }

}