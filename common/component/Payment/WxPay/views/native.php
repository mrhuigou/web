<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/2
 * Time: 15:41
 */

?>
<?php if($jsApiParameters){ ?>
    <a href="javascript:;" class="payment clearfix" id="weixin_js" onclick="callpay()">
        <span class="img wechat"></span>
        <div class="fl">
            <h3>微信安全支付</h3>
            <span class="gray9 f12">安全、方便、快捷</span>
        </div>
        <i class="iconfont fr"></i>
    </a>
<?php } ?>
<?php if($code_url){ ?>
<div class="bd whitebg mt10 tc pb15 mb10" id="weixin_native" style="display: none" >
    <h3 class="f14 fb p10 mb10 bdb">微信扫码支付</h3>
    <p class="mb15 f12">遇到不允许跨号支付？</p>
    <div align="center" id="qrcode">
    </div>
    <p class="pt10 f12">长按图片【识别二维码】付款</p>
</div>
<?php } ?>
<?php $this->beginBlock('JS_END') ?>
<?php if($code_url){ ?>
var url = "<?=$code_url?>";
var qr = qrcode(10, 'L');
qr.addData(url);
qr.make();
var code=document.createElement('DIV');
code.innerHTML = qr.createImgTag();
var element=document.getElementById("qrcode");
element.appendChild(code);
<?php } ?>
<?php if($jsApiParameters){ ?>
function jsApiCall()
{
    WeixinJSBridge.invoke('getBrandWCPayRequest',<?=$jsApiParameters?>,function(res){
    if(res.err_msg=="get_brand_wcpay_request:ok"){
    location.href='<?=\yii\helpers\Url::to(['checkout/complate','trade_no'=>$out_trade_no],true)?>';
    }else{
         $("#weixin_js").hide();
        $("#weixin_native").show();
    }
});
}
function callpay()
{
if (typeof WeixinJSBridge == "undefined"){
if( document.addEventListener ){
document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
}else if (document.attachEvent){
document.attachEvent('WeixinJSBridgeReady', jsApiCall);
document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
}
}else{
jsApiCall();
}
}
<?php } ?>
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>