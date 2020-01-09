<?php

namespace api\controllers\payment;
use api\models\V1\CheckoutOrder;
use api\models\V1\WeixinUserLast;
use common\component\Payment\WxPay\NativeCall_pub;
use common\component\Payment\WxPay\UnifiedOrder_pub;
use common\component\Payment\WxPay\WxPayConf_pub;
use Yii;
use common\component\Payment\WxPay\Notify_pub;
class WeixinController extends \yii\web\Controller
{
    //
    public function actionIndex(){
        //使用通用通知接口
        $notify = new Notify_pub();
        //存储微信的回调
        $xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        Yii::error("【通信跟踪1】:\n".$xml."\n");
    if($xml){
        $notify->saveData($xml);
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        Yii::error("【通信跟踪2】:\n".$returnXml."\n");
        if($notify->checkSign() == TRUE){
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                Yii::error("【通信出错】:\n".$xml."\n");
            }
            elseif($notify->data["result_code"] == "FAIL"){
                //此处应该更新一下订单状态，商户自行增删操作
                Yii::error("【业务出错】:\n".$xml."\n");
            }
            else{
                //此处应该更新一下订单状态，商户自行增删操作
                Yii::error("【支付成功】:\n".$xml."\n");
            }
            //订单号
            $out_trade_no=$notify->data["out_trade_no"];
            if(strpos($out_trade_no,"_NATIVE")){
                $out_trade_no=str_replace("_NATIVE","",$out_trade_no);
            }
            if(strpos($out_trade_no,"_MWEB")){
                $out_trade_no=str_replace("_MWEB","",$out_trade_no);
            }
            if(strpos($out_trade_no,"_JSAPI")){
                $out_trade_no=str_replace("_JSAPI","",$out_trade_no);
            }
            //支付流水号
            $transaction_id=$notify->data["transaction_id"];
            $model=new CheckoutOrder();
            $model->out_trade_no=$out_trade_no;
            $model->transaction_id=$transaction_id;
            $model->payment_method='微信支付';
            $model->payment_code='WeiXin_Pay';
            $model->remak=$xml;
            $model->staus=2;
            $model->save();
            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
	        $this->saveLog($notify->data['openid']);
            echo $returnXml;
        }

    }

    }
	protected function saveLog($openid){
		if(!$model=WeixinUserLast::findOne(['open_id'=>$openid])){
			$model=new WeixinUserLast();
			$model->open_id=$openid;
		}
		$model->last_at=time();
		$model->save();
	}
    /**
     * Native（原生）支付模式一demo
     * ====================================================
     * 模式一：商户按固定格式生成链接二维码，用户扫码后调微信
     * 会将productid和用户openid发送到商户设置的链接上，商户收到
     * 请求生成订单，调用统一支付接口下单提交到微信，微信会返回
     * 给商户prepayid。
     * 本例程对应的二维码由native_call_qrcode.php生成；
     * 本例程对应的响应服务为native_call.php;
     * 需要两者配合使用。
     *
     */
    public function actionNativecall(){
        //使用native通知接口
        $nativeCall = new NativeCall_pub();
        //存储微信的回调
        $xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        if($xml) {
            $nativeCall->saveData($xml);
            if ($nativeCall->checkSign() == FALSE) {
                $nativeCall->setReturnParameter("return_code", "FAIL");//返回状态码
                $nativeCall->setReturnParameter("return_msg", "签名失败");//返回信息
            } else {
                //提取product_id
                $product_id = $nativeCall->getProductId();
                //提取open_id
                $openid = $nativeCall->getOpenId();
                //使用统一支付接口
                $unifiedOrder = new UnifiedOrder_pub();
                //根据不同的$product_id设定对应的下单参数，此处只举例一种
                switch ($product_id) {
                    case WxPayConf_pub::APPID . "static"://与native_call_qrcode.php中的静态链接二维码对应
                        //设置统一支付接口参数
                        //设置必填参数
                        //appid已填,商户无需重复填写
                        //mch_id已填,商户无需重复填写
                        //noncestr已填,商户无需重复填写
                        //spbill_create_ip已填,商户无需重复填写
                        //sign已填,商户无需重复填写
                        $unifiedOrder->setParameter("body", "测试贡献一分钱");//商品描述
                        //自定义订单号，此处仅作举例
                        $timeStamp = time();
                        $out_trade_no = WxPayConf_pub::APPID . "$timeStamp";
                        $unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
                        $unifiedOrder->setParameter("product_id", "$product_id");//商品ID
                        $unifiedOrder->setParameter("total_fee", "1");//总金额
                        $unifiedOrder->setParameter("notify_url", WxPayConf_pub::NOTIFY_URL);//通知地址
                        $unifiedOrder->setParameter("trade_type", "NATIVE");//交易类型
                        $unifiedOrder->setParameter("openid", "$openid");//用户标识
                        //获取prepay_id
                        $prepay_id = $unifiedOrder->getPrepayId();
                        //设置返回码
                        //设置必填参数
                        //appid已填,商户无需重复填写
                        //mch_id已填,商户无需重复填写
                        //noncestr已填,商户无需重复填写
                        //sign已填,商户无需重复填写
                        $nativeCall->setReturnParameter("return_code", "SUCCESS");//返回状态码
                        $nativeCall->setReturnParameter("result_code", "SUCCESS");//业务结果
                        $nativeCall->setReturnParameter("prepay_id", "$prepay_id");//预支付ID
                        break;
                    default:
                        //设置返回码
                        //设置必填参数
                        //appid已填,商户无需重复填写
                        //mch_id已填,商户无需重复填写
                        //noncestr已填,商户无需重复填写
                        //sign已填,商户无需重复填写
                        $nativeCall->setReturnParameter("return_code", "SUCCESS");//返回状态码
                        $nativeCall->setReturnParameter("result_code", "FAIL");//业务结果
                        $nativeCall->setReturnParameter("err_code_des", "此商品无效");//业务结果
                        break;
                }
            }
            //将结果返回微信
            $returnXml = $nativeCall->returnXml();
            echo $returnXml;
            //交易完成
        }
    }

}
