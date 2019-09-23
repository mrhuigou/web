<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/4
 * Time: 10:12
 */
namespace api\controllers\payment;
use api\models\V1\CheckoutOrder;
use api\models\V1\Setting;
use common\component\Payment\Allinpay\AllinpaySubmit;

require_once("../../common/component/Payment/Allinpay/php_rsa.php");

class AllinpayController extends \yii\web\Controller
{
    //数据验证
    private function validateAlinpay(){

        $allinpay_merchant = Setting::find()->where(['key'=>'allinpay_merchant'])->one();
        $merchantId = $allinpay_merchant->value;
        $version= \Yii::$app->request->post('version')? \Yii::$app->request->post('version'):'';
        $language= \Yii::$app->request->post('language')? \Yii::$app->request->post('language'):'';
        $signType= \Yii::$app->request->post('signType') ? \Yii::$app->request->post('signType') :'';
        $payType= \Yii::$app->request->post('payType')? \Yii::$app->request->post('payType') :'';
        $issuerId= \Yii::$app->request->post('issuerId')? \Yii::$app->request->post('issuerId') :'';
        $paymentOrderId= \Yii::$app->request->post('paymentOrderId') ? \Yii::$app->request->post('paymentOrderId'):'';
        $orderNo= \Yii::$app->request->post('orderNo')? \Yii::$app->request->post('orderNo') :'';
        $orderDatetime= \Yii::$app->request->post('orderDatetime')? \Yii::$app->request->post('orderDatetime') :'';
        $orderAmount= \Yii::$app->request->post('orderAmount')? \Yii::$app->request->post('orderAmount') :'';
        $payDatetime= \Yii::$app->request->post('payDatetime')? \Yii::$app->request->post('payDatetime') :'';
        $payAmount= \Yii::$app->request->post('payAmount')? \Yii::$app->request->post('payAmount') :'';
        $ext1= \Yii::$app->request->post('ext1')? \Yii::$app->request->post('ext1') :'';
        $ext2= \Yii::$app->request->post('ext2')? \Yii::$app->request->post('ext2') :'';
        $payResult= \Yii::$app->request->post('payResult') ?  \Yii::$app->request->post('payResult') :'';
        $errorCode= \Yii::$app->request->post('errorCode') ?  \Yii::$app->request->post('errorCode') :'';
        $returnDatetime= \Yii::$app->request->post('returnDatetime')?  \Yii::$app->request->post('returnDatetime') :'';
        $signMsg= \Yii::$app->request->post("signMsg")? \Yii::$app->request->post("signMsg") :'';

        $bufSignSrc="";
        if($merchantId !== "")
            $bufSignSrc=$bufSignSrc."merchantId=".$merchantId."&";
        if($version !== "")
            $bufSignSrc=$bufSignSrc."version=".$version."&";
        if($language !== "")
            $bufSignSrc=$bufSignSrc."language=".$language."&";
        if($signType !== "")
            $bufSignSrc=$bufSignSrc."signType=".$signType."&";
        if($payType !== "")
            $bufSignSrc=$bufSignSrc."payType=".$payType."&";
        if($issuerId !== "")
            $bufSignSrc=$bufSignSrc."issuerId=".$issuerId."&";
        if($paymentOrderId !== "")
            $bufSignSrc=$bufSignSrc."paymentOrderId=".$paymentOrderId."&";
        if($orderNo !== "")
            $bufSignSrc=$bufSignSrc."orderNo=".$orderNo."&";
        if($orderDatetime !== "")
            $bufSignSrc=$bufSignSrc."orderDatetime=".$orderDatetime."&";
        if($orderAmount !== "")
            $bufSignSrc=$bufSignSrc."orderAmount=".$orderAmount."&";
        if($payDatetime !== "")
            $bufSignSrc=$bufSignSrc."payDatetime=".$payDatetime."&";
        if($payAmount !== "")
            $bufSignSrc=$bufSignSrc."payAmount=".$payAmount."&";
        if($ext1 !== "")
            $bufSignSrc=$bufSignSrc."ext1=".$ext1."&";
        if($ext2 !== "")
            $bufSignSrc=$bufSignSrc."ext2=".$ext2."&";
        if($payResult !== "")
            $bufSignSrc=$bufSignSrc."payResult=".$payResult."&";
        if($errorCode !== "")
            $bufSignSrc=$bufSignSrc."errorCode=".$errorCode."&";
        if($returnDatetime !== "")
            $bufSignSrc=$bufSignSrc."returnDatetime=".$returnDatetime;

        //验签
        //解析publickey.txt文本获取公钥信息
        $allinpay_submit = new AllinpaySubmit();
        $publickeycontent = file_get_contents( $allinpay_submit->allinpaykey);

        $publickeyarray = explode(PHP_EOL, $publickeycontent);
        $publickey = explode('=',$publickeyarray[0]);
        $modulus = explode('=',$publickeyarray[1]);
        //echo "<br>publickey=".$publickey[1];
        //echo "<br>modulus=".$modulus[1];

        $keylength = 1024;
        //验签结果
        $verifyResult = rsa_verify($bufSignSrc,$signMsg, trim($publickey[1]), trim($modulus[1]), $keylength,"sha1");
        return $verifyResult;
    }
    public function actionIndex(){

        $paymentOrderId= \Yii::$app->request->post('paymentOrderId') ? \Yii::$app->request->post('paymentOrderId'):'';
        $payResult= \Yii::$app->request->post('payResult') ? \Yii::$app->request->post('payResult'):'';
        $orderNo= \Yii::$app->request->post('orderNo')? \Yii::$app->request->post('orderNo') :'';

        $verifyResult = $this->validateAlinpay();
        $verify_Result = null;
        $pay_Result = null;
        if($verifyResult){
            $verify_Result = "报文验签成功!";
            if($payResult == 1){
                $pay_Result = "订单支付成功!";
                // 完成订单。
                $transaction_id=$paymentOrderId; //payment_deal_no 交易流水号
                $model=new CheckoutOrder();
                $model->out_trade_no=$orderNo; //merge_code
                $model->transaction_id=$transaction_id;
                $model->payment_method='通联在线支付';
                $model->payment_code='allinpay';
                $model->remak=serialize(\Yii::$app->request->post());
                $model->staus=2;
                $model->save();
            }else{
                $pay_Result = "订单支付失败!";
            }
        }else{
            $verify_Result = "报文验签失败!";
            $pay_Result = "因报文验签失败，订单支付失败!";
        }
        echo $verify_Result.$pay_Result;
    }
}