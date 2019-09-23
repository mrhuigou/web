<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/4
 * Time: 10:12
 */
namespace api\controllers\payment;
use api\models\V1\CheckoutOrder;
use common\component\Payment\Alipay\AlipayNotify;

class AlipayController extends \yii\web\Controller
{
    public function actionIndex(){
        try {
            //计算得出通知验证结果
            $alipayNotify = new AlipayNotify();
            $verify_result = $alipayNotify->verifyNotify();
            //验证成功
            if($verify_result) {
                //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
                //商户订单号
                $out_trade_no= \Yii::$app->request->post('out_trade_no');
                //支付宝交易号
                $trade_no = \Yii::$app->request->post('trade_no');
                //交易状态
                $trade_status =\Yii::$app->request->post('trade_status');

                if(strtoupper($trade_status) == 'TRADE_FINISHED') {
                    //移动sdk不支持退款，其成功状态就是 FINISHED
                    //支付流水号
                    $transaction_id=$trade_no;
                    $model=new CheckoutOrder();
                    $model->out_trade_no=$out_trade_no;
                    $model->transaction_id=$transaction_id;
                    $model->payment_method='支付宝';
                    $model->payment_code='alipay';
                    $model->remak=serialize(\Yii::$app->request->post());
                    $model->staus=2;
                    $model->save();
                }else if (strtoupper($trade_status) == 'TRADE_SUCCESS') {
                    //支付流水号
                    $transaction_id=$trade_no;
                    $model=new CheckoutOrder();
                    $model->out_trade_no=$out_trade_no;
                    $model->transaction_id=$transaction_id;
                    $model->payment_method='支付宝';
                    $model->payment_code='alipay';
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
            \Yii::error("【业务出错】:\n".serialize(\Yii::$app->request->post())."\n");
            return false;
        }
    }

}