<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/2
 * Time: 15:12
 */
namespace common\component\Payment\WxPay;
use Yii;
use yii\web\AssetBundle;

class WxpayWidget extends \yii\base\Widget{
    public $out_trade_no;
    public $total_fee;
    public $body="每日惠购订单";
    public $notify_url="";
    public function init()
    {
        if(!$this->notify_url){
            $this->notify_url=WxPayConf_pub::NOTIFY_URL;
        }
        parent::init();

    }
    public function run()
    {
        $useragent=\Yii::$app->request->getUserAgent();
        if(strpos(strtolower($useragent), 'micromessenger') === false){
            return;
        }
       $code_url = $this->WxNavitePay();
        if($openid = \Yii::$app->session->get('open_id')){
            $jsApiParameters=$this->WxJsPay($openid);
        }else{
            $jsApiParameters="";
        }
        $this->registerClientScript();
        return $this->render('native',[
            'code_url'=>$code_url,
            'jsApiParameters'=>$jsApiParameters,
            'out_trade_no'=>$this->out_trade_no,
        ]);
    }

    protected function WxJsPay($openid){
            //使用jsapi接口
            $jsApi = new JsApi_pub();
            //=========步骤2：使用统一支付接口，获取prepay_id============
            //使用统一支付接口
            $unifiedOrder = new UnifiedOrder_pub();
            //设置统一支付接口参数
            //设置必填参数
            $unifiedOrder->setParameter("openid", "$openid");//用户ID
            $unifiedOrder->setParameter("body","$this->body");//商品描述
            $unifiedOrder->setParameter("out_trade_no", "$this->out_trade_no");//商户订单号
            $unifiedOrder->setParameter("total_fee", $this->total_fee);//总金额
            $unifiedOrder->setParameter("notify_url", $this->notify_url);//通知地址
            $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
            $prepay_id = $unifiedOrder->getPrepayId();
            $jsApi->setPrepayId($prepay_id);
            $jsApiParameters = $jsApi->getParameters();
            return $jsApiParameters;
    }

    protected function WxNavitePay(){
        $result="";
        //使用统一支付接口
        $unifiedOrder = new UnifiedOrder_pub();
        $unifiedOrder->setParameter("body","$this->body");//商品描述
        //自定义订单号，此处仅作举例
        $unifiedOrder->setParameter("out_trade_no",$this->out_trade_no."_NATIVE");//商户订单号
        $unifiedOrder->setParameter("total_fee","$this->total_fee");//总金额
        $unifiedOrder->setParameter("notify_url",$this->notify_url);//通知地址
        $unifiedOrder->setParameter("trade_type","NATIVE");//交易类型
        //获取统一支付接口结果
        $unifiedOrderResult = $unifiedOrder->getResult();
        //商户根据实际情况设置相应的处理流程
        if ($unifiedOrderResult["return_code"] == "FAIL")
        {
            //商户自行增加处理流程
            Yii::error("通信出错：".$unifiedOrderResult['return_msg']);
        }
        elseif($unifiedOrderResult["result_code"] == "FAIL")
        {
            //商户自行增加处理流程
            Yii::error("错误代码：".$unifiedOrderResult['err_code']);
            Yii::error("错误代码描述：".$unifiedOrderResult['err_code_des']);
        }
        elseif($unifiedOrderResult["code_url"] != NULL)
        {
            //从统一支付接口获取到code_url
            $result = $unifiedOrderResult["code_url"];
        }
        return $result;
    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript()
    {
        WxpayAsset::register($this->view);
    }
}