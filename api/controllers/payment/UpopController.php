<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/4
 * Time: 10:12
 */
namespace api\controllers\payment;
use api\models\V1\CheckoutOrder;
use common\component\Payment\Upop\quickpay_conf;
use common\component\Payment\Upop\quickpay_service;

class UpopController extends \yii\web\Controller
{
    public function actionIndex(){
        try {
            $response = new quickpay_service(\Yii::$app->request->post(), quickpay_conf::RESPONSE);
            if ($response->get('respCode') != quickpay_service::RESP_SUCCESS)
            {
                $err = sprintf("Error: %d => %s", $response->get('respCode'), $response->get('respMsg'));
                throw new \Exception($err);
            }
            $arr_ret = $response->get_args();
            $order_sn		= $arr_ret['orderNumber'];
            $payment_amount = (int)$arr_ret['settleAmount'];
            // 检查商户账号是否一致。
            if (quickpay_conf::$pay_params['merId'] != $arr_ret['merId'])
            {
                return false;
            }
            // 如果未支付成功。
            if ($arr_ret['respCode'] != '00')
            {
                return false;
            }
            $trade_no=$arr_ret['qid'];

            // 完成订单。
            //订单号
            $out_trade_no=$order_sn;
            //支付流水号
            $transaction_id=$trade_no;
            $model=new CheckoutOrder();
            $model->out_trade_no=$out_trade_no;
            $model->transaction_id=$transaction_id;
            $model->payment_method='银联在线支付';
            $model->payment_code='upop';
            $model->remak=serialize(\Yii::$app->request->post());
            $model->staus=2;
            $model->save();
            //告诉用户交易完成
            return true;

        }
        catch(\Exception $exp)
        {
            \Yii::error("【业务出错】:\n".serialize(\Yii::$app->request->post())."\n");
            return false;
        }
    }


}