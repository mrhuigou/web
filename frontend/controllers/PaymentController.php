<?php

namespace frontend\controllers;
use api\models\V1\CheckoutOrder;
use api\models\V1\CustomerTransaction;
use api\models\V1\Order;
use api\models\V1\OrderMerge;
use common\component\Helper\OrderSn;
use common\component\Payment\Alipay\AlipayConfig;
use common\component\Payment\Alipay\AlipaySubmit;
use common\component\Payment\Allinpay\AllinpaySubmit;
use common\component\Payment\Upop\quickpay_conf;
use common\component\Payment\Upop\quickpay_service;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use Yii;
class PaymentController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
        }
        $trade_no=Yii::$app->request->get('trade_no');
        try{
            if($model=OrderMerge::findOne(['merge_code'=>$trade_no])){
                if($model->status==1){
                    return $this->redirect(['/checkout/complate','trade_no'=>$model->merge_code]);
                }else{
                    if(bccomp($model->getMergeTotal(),$model->total)!==0){
                        $model->status=-1;
                        $model->date_modified = date("Y-m-d H:i:s");
                        $model->save();
                        throw new NotFoundHttpException("交易订单已经过期！");
                    }
                }
                return $this->render('index',['model'=>$model]);
            }else{
                throw new NotFoundHttpException("交易订单不存在！");
            }
        }catch (NotFoundHttpException $e){
            throw new NotFoundHttpException($e->getMessage());
        }
    }



    public function actionTrade(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getReferrer()]);
        }
        try{
            if($action=Yii::$app->request->post('payment_method')){
                $trade_no=Yii::$app->request->get('trade_no');
                if(strpos($action,'_')){
                    list($act,$bankId)=explode('_',$action);
                    $payment_method_type=Yii::$app->request->post('payment_method_type');
                    return $this->runAction($act,['trade_no'=>$trade_no,'bankId'=>$bankId,'payment_method_type'=>$payment_method_type]);
                }else{
                    if($action=='balance'){
                        $payment_pwd=Yii::$app->request->post('payment_pwd');
                        return $this->runAction($action,['trade_no'=>$trade_no,'payment_pwd'=>$payment_pwd]);
                    }else{
                        return $this->runAction($action,['trade_no'=>$trade_no]);
                    }
                }
            }else{
                throw new NotFoundHttpException("非法请求！");
            }
        }catch (NotFoundHttpException $e){
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
            return $this->redirect(Yii::$app->request->getReferrer());
        }
    }
    public function actionCod($trade_no){
        try {
            if(!$order=OrderMerge::findOne(['merge_code'=>$trade_no,'customer_id'=>Yii::$app->user->getId()])){
                throw new NotFoundHttpException("交易订单不存在！");
            }
            if($order->status){
                return $this->redirect('/order/index');
            }
            $have_recharge = false;
            $order_id_str = str_replace("，", ",", $order->order_ids);
            $orderIds = explode(",", $order_id_str);
            foreach($orderIds as $order_id){
                $order_info = Order::findOne($order_id);
                if($order_info->order_type_code == 'recharge'){
                    $have_recharge = true;
                }
            }
            if($order->total>0 && !$have_recharge){
                $model=new CheckoutOrder();
                $model->out_trade_no=$order->merge_code;
                $model->transaction_id=$order->merge_code;
                $model->staus=12;
                $model->payment_method="货到付款";
                $model->payment_code="cod";
                $model->remak=$order->merge_code;
                $model->save();
                return $this->redirect(['/checkout/complate','trade_no'=>$order->merge_code]);
            }else{
                throw new NotFoundHttpException("非法操作！");
            }
        }catch (\Exception $e){
            throw new NotFoundHttpException($e->getMessage());
        }
    }
    public function actionFreeCheckout($trade_no){
        try {
            if(!$order=OrderMerge::findOne(['merge_code'=>$trade_no,'customer_id'=>Yii::$app->user->getId()])){
                throw new NotFoundHttpException("交易订单不存在！");
            }
            if($order->status){
                return $this->redirect('/order/index');
            }
            if($order->total==0){
                $model=new CheckoutOrder();
                $model->out_trade_no=$order->merge_code;
                $model->transaction_id=$order->merge_code;
                $model->staus=2;
                $model->payment_method="免费支付";
                $model->payment_code="free_checkout";
                $model->remak=$order->merge_code;
                $model->save();
               return $this->redirect(['/checkout/complate','trade_no'=>$order->merge_code]);
            }else{
                throw new NotFoundHttpException("非法操作！");
            }
        }catch (\Exception $e){
            throw new NotFoundHttpException($e->getMessage());
       }
     }
//银联在线ok
    public function actionUpop($trade_no){
        try{
            if (!$order = OrderMerge::findOne(['merge_code' => $trade_no, 'customer_id' => Yii::$app->user->getId()])) {
                throw new \Exception("交易订单不存在！");
            }
            if($order->status){
                return $this->redirect('/order/index');
            }
            Yii::$app->session->set('Pay_trade_no',$order->merge_code);
            $param['transType']             = quickpay_conf::CONSUME;  //交易类型，CONSUME or PRE_AUTH
            $param['orderAmount']           = $order->total * 100;        //交易金额
            $param['orderNumber']           = $order->merge_code; //订单号，必须唯一
            $param['orderTime']             = date('YmdHis',time());   //交易时间, YYYYmmhhddHHMMSS
            $param['orderCurrency']         = quickpay_conf::CURRENCY_CNY;  //交易币种，CURRENCY_CNY=>人民币
            $param['customerIp']            = Yii::$app->request->getUserIP();  //用户IP
            $param['frontEndUrl']           = Url::to(['/checkout/complate','trade_no'=>$order->merge_code],true);   //前台回调URL
            $param['backEndUrl']            ="https://open.mrhuigou.com/payment/upop";   //后台回调URL
            $pay_service = new quickpay_service($param, quickpay_conf::FRONT_PAY);
            return $pay_service->create_html();
        }catch (\Exception $e){
            throw new NotFoundHttpException($e->getMessage());
        }
    }
    //支付宝支付 ok
    public function actionAlipay($trade_no){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        try{
            if (!$order = OrderMerge::findOne(['merge_code' => $trade_no, 'customer_id' => Yii::$app->user->getId()])) {
                throw new \Exception("交易订单不存在！");
            }
            if($order->status){
                return $this->redirect('/order/index');
            }
            Yii::$app->session->set('Pay_trade_no',$order->merge_code);
            //支付类型
            $payment_type = "1";
            //必填，不能修改
            //服务器异步通知页面路径
            $notify_url ="https://open.mrhuigou.com/payment/alipay";   //后台回调URL
            //需http://格式的完整路径，不能加?id=123这类自定义参数

            //页面跳转同步通知页面路径
            $return_url =Url::to(['/checkout/complate','trade_no'=>$order->merge_code],true);
            //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

            //商户订单号
            $out_trade_no = $order->merge_code;
            //商户网站订单系统中唯一订单号，必填

            //订单名称
            $subject = "每日惠购订单";
            //必填

            //付款金额
            $total_fee = number_format($order['total'], 2,'.',''); //order_merge
            //必填

            //商品展示地址
            $show_url = Url::to('/order/index',true);
            //必填，需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

            //订单描述
            $body ="订单编号:".$order->merge_code;
            //选填
            //超时时间
            $it_b_pay="";
            //选填
            //钱包token
            $extern_token ="";
            //选填
            //构造要请求的参数数组，无需改动
            $parameter = array(
                //"service" => "alipay.wap.create.direct.pay.by.user",
                "service" => "create_direct_pay_by_user",
                "partner" => trim(AlipayConfig::$partner),
                "seller_id" => trim(AlipayConfig::$seller_id),
                "payment_type"	=> $payment_type,
                "notify_url"	=> $notify_url,
                "return_url"	=> $return_url,
                "out_trade_no"	=> $out_trade_no,
                "subject"	=> $subject,
                "total_fee"	=> $total_fee,
                "show_url"	=> $show_url,
                "body"	=> $body,
                "it_b_pay"	=> $it_b_pay,
                "extern_token"	=> $extern_token,
                "_input_charset"	=> trim(strtolower(AlipayConfig::$input_charset))
            );
            //建立请求
            $alipaySubmit = new AlipaySubmit();
            return  $alipaySubmit->buildRequestForm($parameter,"get");
        }catch (\Exception $e){
            throw new NotFoundHttpException($e->getMessage());
        }
    }
    //通联 wrong
    public function actionAllinpay($trade_no,$bankId,$payment_method_type=1){
        try {
            if (!$order = OrderMerge::findOne(['merge_code' => $trade_no, 'customer_id' => Yii::$app->user->getId()])) {
                throw new \Exception("交易订单不存在！");
            }
            if ($order->status) {
                return $this->redirect('/order/index');
            }
            Yii::$app->session->set('Pay_trade_no',$order->merge_code);
            $serverUrl ="https://service.allinpay.com/gateway/index.do";
            $inputCharset=1;
            $pickupUrl= Url::to('/checkout/complate',true);;   //前台回调URL
            $receiveUrl="https://open.mrhuigou.com/payment/allinpay";   ; //后台回调URL
            $version='v1.0';
            $language=1;
            $signType=1;
            $merchantId ='109215321311001';
            $key='7758521521';
            $payerName='';
            $payerEmail='';
            $payerTelephone='';
            $payerIDCard='';
            $pid='';
            $orderNo=$order->merge_code;
            $orderAmount=number_format($order->total, 2,'.','')*100;        //交易金额
            $orderDatetime=date('YmdHis');   //交易时间, YYYYmmhhddHHMMSS
            $orderCurrency='0';
            $orderExpireDatetime=60*24;
            $productName='家润同城-交易编号：'.$order->merge_code;
            $productId='';
            $productPrice='';
            $productNum='';
            $productDesc='家润同城-交易编号：'.$order->merge_code;
            $ext1='';
            $ext2='';
            $extTL='';
            if($bankId){
                $payType=$payment_method_type; //payType   不能为空，必须放在表单中提交。
                $issuerId=$bankId; //issueId 直联时不为空，必须放在表单中提交。
            }else{
                $payType=$payment_method_type; //payType   不能为空，必须放在表单中提交。
                $issuerId=''; //issueId 直联时不为空，必须放在表单中提交。
            }
            $pan='';
            $tradeNature='';
            //报文参数有消息校验
            //if(preg_match("/\d/",$pickupUrl)){
            //echo "<script>alert('pickupUrl有误！！');history.back();</script>";
            //}

            // 生成签名字符串。
            $bufSignSrc="";
            if($inputCharset !== "")
                $bufSignSrc = $bufSignSrc."inputCharset=".$inputCharset."&";
            if($pickupUrl !== "")
                $bufSignSrc = $bufSignSrc."pickupUrl=".$pickupUrl."&";
            if($receiveUrl !== "")
                $bufSignSrc = $bufSignSrc."receiveUrl=".$receiveUrl."&";
            if($version !== "")
                $bufSignSrc = $bufSignSrc."version=".$version."&";
            if($language !== "")
                $bufSignSrc = $bufSignSrc."language=".$language."&";
            if($signType !== "")
                $bufSignSrc = $bufSignSrc."signType=".$signType."&";
            if($merchantId !== "")
                $bufSignSrc = $bufSignSrc."merchantId=".$merchantId."&";
            if($payerName !== "")
                $bufSignSrc = $bufSignSrc."payerName=".$payerName."&";
            if($payerEmail !== "")
                $bufSignSrc = $bufSignSrc."payerEmail=".$payerEmail."&";
            if($payerTelephone !== "")
                $bufSignSrc = $bufSignSrc."payerTelephone=".$payerTelephone."&";
            if($payerIDCard !== "")
                $bufSignSrc = $bufSignSrc."payerIDCard=".$payerIDCard."&";
            if($pid !== "")
                $bufSignSrc = $bufSignSrc."pid=".$pid."&";
            if($orderNo !== "")
                $bufSignSrc = $bufSignSrc."orderNo=".$orderNo."&";
            if($orderAmount !== "")
                $bufSignSrc = $bufSignSrc."orderAmount=".$orderAmount."&";
            if($orderCurrency !== "")
                $bufSignSrc = $bufSignSrc."orderCurrency=".$orderCurrency."&";
            if($orderDatetime !== "")
                $bufSignSrc = $bufSignSrc."orderDatetime=".$orderDatetime."&";
            if($orderExpireDatetime !== "")
                $bufSignSrc = $bufSignSrc."orderExpireDatetime=".$orderExpireDatetime."&";
            if($productName !== "")
                $bufSignSrc = $bufSignSrc."productName=".$productName."&";
            if($productPrice !== "")
                $bufSignSrc = $bufSignSrc."productPrice=".$productPrice."&";
            if($productNum !== "")
                $bufSignSrc = $bufSignSrc."productNum=".$productNum."&";
            if($productId !== "")
                $bufSignSrc = $bufSignSrc."productId=".$productId."&";
            if($productDesc !== "")
                $bufSignSrc = $bufSignSrc."productDesc=".$productDesc."&";
            if($ext1 !== "")
                $bufSignSrc = $bufSignSrc."ext1=".$ext1."&";
            if($ext2 !== "")
                $bufSignSrc = $bufSignSrc."ext2=".$ext2."&";
            if($extTL !== "")
                $bufSignSrc = $bufSignSrc."extTL".$extTL."&";
            if($payType !== "")
                $bufSignSrc = $bufSignSrc."payType=".$payType."&";
            if($issuerId !== "")
                $bufSignSrc = $bufSignSrc."issuerId=".$issuerId."&";
            if($pan !== "")
                $bufSignSrc = $bufSignSrc."pan=".$pan."&";
            if($tradeNature !== "")
                $bufSignSrc = $bufSignSrc."tradeNature=".$tradeNature."&";
            $bufSignSrc = $bufSignSrc."key=".$key; //key为MD5密钥，密钥是在通联支付网关商户服务网站上设置。

            //签名，设为signMsg字段值。
            $signMsg = strtoupper(md5($bufSignSrc));
            //$params['serverUrl']=$serverUrl;
            $params['inputCharset']=$inputCharset;
            $params['pickupUrl']=$pickupUrl;
            $params['receiveUrl']=$receiveUrl;
            $params['version']=$version;
            $params['language']=$language;
            $params['signType']=$signType;
            $params['merchantId']=$merchantId;
            $params['payerName']=$payerName;
            $params['payerEmail']=$payerEmail;
            $params['payerTelephone']=$payerTelephone;
            $params['payerIDCard']=$payerIDCard;
            $params['pid']=$pid;
            $params['orderNo']=$orderNo;
            $params['orderAmount']=$orderAmount;
            $params['orderDatetime']=$orderDatetime;
            $params['orderCurrency']=$orderCurrency;
            $params['orderExpireDatetime']=$orderExpireDatetime;
            $params['productName']=$productName;
            $params['productId']=$productId;
            $params['productPrice']=$productPrice;
            $params['productNum']=$productNum;
            $params['productDesc']=$productDesc;
            $params['ext1']=$ext1;
            $params['ext2']=$ext2;
            $params['extTL']=$extTL;
            $params['payType']=$payType; //payType   不能为空，必须放在表单中提交。
            $params['issuerId']=$issuerId; //issueId 直联时不为空，必须放在表单中提交。
            $params['pan']=$pan;
            $params['tradeNature']=$tradeNature;
            $params['key']=$key;
            $params['signMsg']=$signMsg;
            $form['serverUrl'] = $serverUrl;
            $form['inputCharset'] = $inputCharset;
            $form['method'] = "POST";
            $allinpaySubmit = new AllinpaySubmit();
            return  $allinpaySubmit->buildRequestForm($form,$params,"POST");
        }catch (\Exception $e){
            throw new NotFoundHttpException($e->getMessage());
        }
    }
    public function actionBalance($trade_no,$payment_pwd){
        try {
            if (!$order = OrderMerge::findOne(['merge_code' => $trade_no, 'customer_id' => Yii::$app->user->getId()])) {
                throw new \Exception("交易订单不存在！");
            }
            if ($order->status) {
                return $this->redirect('/order/index');
            }
            if(!Yii::$app->user->identity->validatePayPassword($payment_pwd)){
                throw new \Exception("支付密码错误,请重试！");
            }
            if(bccomp(Yii::$app->user->identity->balance,$order->mergeTotal,2)<0){
                throw new \Exception("余额不足,请先充值后使用！");
            }
            $balance=new CustomerTransaction();
            $balance->customer_id=Yii::$app->user->getId();
            $balance->amount='-'.$order->mergeTotal;
            $balance->trade_no=$order->merge_code;
            $balance->description="购物消费|流水号：".$order->merge_code;
            $balance->date_added=date('Y-m-d H:i:s',time());
            if(!$balance->save()){
                throw new \Exception("扣款失败！");
            }
            $model=new CheckoutOrder();
            $model->out_trade_no=$order->merge_code;
            $model->transaction_id=$order->merge_code;
            $model->staus=2;
            $model->payment_method="余额支付";
            $model->payment_code="balance";
            $model->remak=$order->merge_code;
            if(!$model->save()){
                throw new \Exception("更新失败！");
            }
            return $this->redirect(Url::to(['/checkout/complate','trade_no'=>$order->merge_code],true));
        }catch (\Exception $e){
            throw new NotFoundHttpException($e->getMessage());
        }
    }

}
