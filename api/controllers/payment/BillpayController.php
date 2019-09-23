<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/4
 * Time: 10:12
 */
namespace api\controllers\payment;
use api\models\V1\CheckoutOrder;
use common\component\Payment\Billpay\BillpaySubmit;

class AllinpayController extends \yii\web\Controller
{
    public function actionIndex(){
        $bill_submit = new BillpaySubmit();

        //人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
        $kq_check_all_para=$bill_submit->justify_params(\Yii::$app->request->post('merchantAcctId'),'merchantAcctId');
//网关版本，固定值：v2.0,该值与提交时相同。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('version'),'version');
//语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('language'),'language');
//签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('signType'),'signType');
//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('payType'),'payType');
//银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('bankId'),'bankId');
//商户订单号，,该值与提交时相同。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('orderId'),'orderId');
//订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('orderTime'),'orderTime');
//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('orderAmount'),'orderAmount');
// 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('dealId'),'dealId');
//银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('bankDealId'),'bankDealId');
//快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('dealTime'),'dealTime');
//商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('payAmount'),'payAmount');
//费用，快钱收取商户的手续费，单位为分。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('fee'),'fee');
//扩展字段1，该值与提交时相同
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('ext1'),'ext1');
//扩展字段2，该值与提交时相同。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('ext2'),'ext2');
//处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('payResult'),'payResult');
//错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
        $kq_check_all_para.=$bill_submit->justify_params(\Yii::$app->request->post('errCode'),'errCode');
        $trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
        $MAC=base64_decode(\Yii::$app->request->post('signMsg'));

        $fp = fopen($bill_submit->billpaycert, "r");
        $cert = fread($fp, 8192);
        fclose($fp);
        $pubkeyid = openssl_get_publickey($cert);
        $ok = openssl_verify($trans_body, $MAC, $pubkeyid);


        if ($ok == 1) {
            switch(\Yii::$app->request->post('payResult')){
                case '10':
                    //此处做商户逻辑处理
                    $rtnOK=1;
                    $transaction_id= \Yii::$app->request->post('dealId'); //payment_deal_no 交易流水号
                    $model=new CheckoutOrder();
                    $model->out_trade_no=\Yii::$app->request->post('orderId'); //merge_code
                    $model->transaction_id=$transaction_id;
                    $model->payment_method='快钱支付';
                    $model->payment_code='billpay';
                    $model->remak=serialize(\Yii::$app->request->post());
                    $model->staus=2;
                    $model->save();
                    break;
                default:
                    $rtnOK=1;

                    $transaction_id= \Yii::$app->request->post('dealId'); //payment_deal_no 交易流水号
                    $model=new CheckoutOrder();
                    $model->out_trade_no=\Yii::$app->request->post('orderId'); //merge_code
                    $model->transaction_id=$transaction_id;
                    $model->payment_method='快钱支付';
                    $model->payment_code='billpay';
                    $model->remak=serialize(\Yii::$app->request->post());
                    $model->staus=2;
                    $model->save();
            }
        }else{
            $rtnOK=1;
            //以下是我们快钱设置的show页面，商户需要自己定义该页面。
            $rtnUrl="http://219.233.173.50:8802/futao/rmb_demo/show.php?msg=error";

        }

        echo '<result>'.$rtnOK.'</result> <redirecturl>'. $rtnUrl.'</redirecturl>';

    }

}