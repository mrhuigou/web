<?php

namespace affiliate\controllers;
use api\models\V1\CheckoutOrder;
use api\models\V1\Order;
use api\models\V1\OrderMerge;
use common\component\Payment\Alipay\AlipayConfig;
use common\component\Payment\Alipay\AlipaySubmit;
use common\component\Payment\Bestone\BestoneConfig;
use common\component\Payment\Bestone\BestoneSubmit;
use common\component\Payment\Upop\quickpay_conf;
use common\component\Payment\Upop\quickpay_service;
use common\component\Payment\WxPay\JsApi_pub;
use common\component\Payment\WxPay\UnifiedOrder_pub;
use h5\models\BalanceForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use Yii;
class PaymentController extends \yii\web\Controller
{
    public $layout = 'main_other';
    public function actions(){
        return [
            'wx-js-call' => [
                'class' => 'common\component\Payment\WxPay\WxpayAction',
            ],
        ];
    }
    public function actionIndex()
    {

//        if (\Yii::$app->user->isGuest) {
//            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
//        }

        $fx_user_login_status = false;
        //获取用户登录状态 session 缓存 user_login_status
//        \Yii::$app->session->remove("fx_user_login_status");
        if(\Yii::$app->redis->get("fx_user_login_status")){
            $fx_user_login_status = \Yii::$app->redis->get("fx_user_login_status");
        }
        if (!$fx_user_login_status) {
            return $this->redirect(['/site-mobile/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
        }

        $trade_no=Yii::$app->request->get('trade_no');
        $useragent=\Yii::$app->request->getUserAgent();
        if(strpos(strtolower($useragent), 'micromessenger') && !$open_id=\Yii::$app->session->get('open_id') ?:"123"){
            return $this->redirect(['/payment/wx-js-call','path'=>Url::to(['/payment/index','trade_no'=>$trade_no,'showwxpaytitle'=>1],true)]);
        }
        try{
            if($model=OrderMerge::findOne(['merge_code'=>$trade_no])){
//	            if($model->total==0){
//		            return $this->redirect(['/payment/free-pay','trade_no'=>$model->merge_code]);
//	            }
                if($model->status==1){
                    return $this->redirect(['/checkout/complate','trade_no'=>$model->merge_code]);
                }else{
                   if(bccomp($model->getMergeTotal(),$model->total)!==0){
                       $model->status=-1;
                       $model->date_modified = date("Y-m-d H:i:s");
                       $model->save();
                       throw new NotFoundHttpException("交易订单已经过期！");
                   }
                   if(!$model->getPayStatus()){
	                   throw new NotFoundHttpException("交易订单已经过期！");
                   }
                }
                $fx_user_info = json_decode(\Yii::$app->redis->get("fx_user_info"),true);
                return $this->render('index',['model'=>$model,'fx_user_info'=> $fx_user_info]);
            }else{
                throw new NotFoundHttpException("交易订单不存在！");
            }
        }catch (NotFoundHttpException $e){
            throw new NotFoundHttpException($e->getMessage());
        }
    }
    public function actionWxpay(){
        try{
            if (!$order = OrderMerge::findOne(['merge_code' => \Yii::$app->request->get('trade_no'), 'status' => 0])) {
                throw new NotFoundHttpException("交易订单已过期！");
            }
            if($order->status){
                return $this->redirect('/order/index');
            }
            Yii::$app->session->set('Pay_trade_no',$order->merge_code);
            //使用jsapi接口
            $jsApi = new JsApi_pub();
            //=========步骤2：使用统一支付接口，获取prepay_id============
            //使用统一支付接口
            $unifiedOrder = new UnifiedOrder_pub();
            //设置统一支付接口参数
            //设置必填参数
	        $useragent=\Yii::$app->request->getUserAgent();
            if(strpos(strtolower($useragent), 'micromessenger') &&  ($open_id=\Yii::$app->session->get('open_id'))){
	            $unifiedOrder->setParameter("openid", "$open_id");//用户ID
	            $unifiedOrder->setParameter("body","每日惠购");//商品描述
	            $unifiedOrder->setParameter("out_trade_no", "$order->merge_code"."_JSAPI");//商户订单号
	            $unifiedOrder->setParameter("total_fee", $order->total * 100);//总金额
	            $unifiedOrder->setParameter("notify_url", "https://open.mrhuigou.com/payment/weixin");//通知地址
	            $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
	            $prepay_id = $unifiedOrder->getPrepayId();
	            if($prepay_id){
		            $jsApi->setPrepayId($prepay_id);
		            $jsApiParameters = $jsApi->getParameters();
		            return Json::encode(['status'=>1,'data'=>$jsApiParameters]);
	            }else{
		            throw new NotFoundHttpException("微信支付网关异常！");
	            }
            }else{
	            $unifiedOrder->setParameter("body","每日惠购");//商品描述
	            $unifiedOrder->setParameter("out_trade_no", "$order->merge_code"."_MWEB");//商户订单号
	            $unifiedOrder->setParameter("total_fee", $order->total * 100);//总金额
	            $unifiedOrder->setParameter("notify_url", "https://open.mrhuigou.com/payment/weixin");//通知地址
	            $unifiedOrder->setParameter("trade_type", "MWEB");//交易类型
	            $unifiedOrder->setParameter('scene_info',Json::encode(['h5_info'=>['type'=>'Wap','wap_url'=>"https://m.mrhuigou.com","wap_name"=>"每日惠购"]]));
	            $redirect_url = $unifiedOrder->getWapResult();
	            if($redirect_url){
		            return Json::encode(['status'=>1,'data'=>$redirect_url]);
	            }else{
		            throw new NotFoundHttpException("微信网页支付稍后开通，请选择其它支付方式！");
	            }
            }


        }catch (NotFoundHttpException $e){
            return Json::encode(['status'=>0,'message'=>$e->getMessage()]);
        }
    }
    public function actionFreePay(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
        }
        try {
            if(!$order=OrderMerge::findOne(['merge_code'=>\Yii::$app->request->get('trade_no'),'customer_id'=>Yii::$app->user->getId()])){
                throw new NotFoundHttpException("非法操作！");
            }
            if($order->status){
                return $this->redirect('/order/index');
            }
            Yii::$app->session->set('Pay_trade_no',$order->merge_code);
            if($order->MergeTotal==0){
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
        }catch (NotFoundHttpException $e){
            return $this->render('error',['message'=>$e->getMessage()]);
       }
     }
    public function actionCod(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
        }
        try {
            $trade_no = \Yii::$app->request->get('trade_no');
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

    public function actionUpopPay(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
        }
        try{
            if (!$order = OrderMerge::findOne(['merge_code' => \Yii::$app->request->get('trade_no'), 'status' => 0])) {
                throw new NotFoundHttpException("非法操作！");
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
            $param['customerIp']            = '';  //用户IP
            $param['frontEndUrl']           = Url::to(['/checkout/complate','trade_no'=>$order->merge_code],true);   //前台回调URL
            $param['backEndUrl']            ="https://open.mrhuigou.com/payment/upop";   //后台回调URL
            $pay_service = new quickpay_service($param, quickpay_conf::FRONT_PAY);
            return $pay_service->create_html();
        }catch (NotFoundHttpException $e){
            return $this->render('error',['message'=>$e->getMessage()]);
        }
    }
    public function actionAlipay(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
        }
        try{
            if (!$order = OrderMerge::findOne(['merge_code' => \Yii::$app->request->get('trade_no'), 'status' => 0])) {
                throw new NotFoundHttpException("非法操作！");
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
            $return_url =Url::to('/checkout/complate',true);
            //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

            //商户订单号
            $out_trade_no =$order->merge_code;
            //商户网站订单系统中唯一订单号，必填

            //订单名称
            $subject = "每日惠购订单";
            //必填

            //付款金额
            $total_fee = number_format($order['total'], 2,'.','');
            //必填

            //商品展示地址
            $show_url = Url::to(['/checkout/complate','trade_no'=>$order->merge_code],true);
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
                "service" => "alipay.wap.create.direct.pay.by.user",
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
        }catch (NotFoundHttpException $e){
            return $this->render('error',['message'=>$e->getMessage()]);
        }
    }
    public function actionBalance(){
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = Yii::$app->request->getAbsoluteUrl();
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
        if(!Yii::$app->user->identity->paymentpwd){
            return $this->redirect(['/user/security-update-paymentpwd','redirect'=>$url]);
        }
        $trade_no=\Yii::$app->request->get('trade_no');
        try {
            $model = new BalanceForm($trade_no);
            if ($model->load(Yii::$app->request->post()) && $model->submit()) {
                return $this->redirect(['/checkout/complate', 'trade_no' => $trade_no]);
            } else {
                return $this->render('balance', ['model' => $model]);
            }
        }catch (NotFoundHttpException $e){
            return $this->render('error',['message'=>$e->getMessage()]);
        }
    }
    public function actionBestonepay(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
        }
        try{
            if (!$order = OrderMerge::findOne(['merge_code' => \Yii::$app->request->get('trade_no'), 'status' => 0])) {
                throw new NotFoundHttpException("非法操作！");
            }
            if($order->status){
                return $this->redirect('/order/index');
            }
            Yii::$app->session->set('Pay_trade_no',$order->merge_code);

			//页面跳转同步通知页面路径
            $return_url = Url::to(['/checkout/complate','trade_no'=>$order->merge_code],true);
			//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/


			$order_no = $order->merge_code;;
			//商户网站订单系统中唯一订单号，必填
			//订单名称
            $subject = "每日惠购订单";
			//必填
			//付款金额
			$amount = number_format($order['total'], 2,'.','');
			//必填
			//订单描述
			$body = "订单编号:".$order->merge_code;
			//默认支付方式
			$paymethod = "precardPay";

            $parameter = array(
                "service" => "payorder",
                "version" => "B2C1.0",
                "partner" => BestoneConfig::$appid,
                "notify_url"	=> BestoneConfig::$notify_url,
                "return_url"	=> $return_url,
                "mall_email" => BestoneConfig::$mall_email,//卖家email帐户
                "orderno" => $order_no,
                "ordertime" => date("YmdHis"),
                "subject"	=> $subject,
                "amount"    => $amount,
                "paymethod" => $paymethod,
                //"body" => $body,
            );

            $best_one_submit = new BestoneSubmit();
            //$sign = $best_one_submit->hmac($parameter,BestoneConfig::$appkey);

            $sign = $best_one_submit->getSign($parameter);

            $parameter['sign'] = $sign;
            $parameter['sign_type'] = 'HmacMD5';

            //建立请求

            return  $best_one_submit->create_html($parameter);
        }catch (NotFoundHttpException $e){
            return $this->render('error',['message'=>$e->getMessage()]);
        }
    }


}
