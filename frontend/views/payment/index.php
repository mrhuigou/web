<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = "我的收银台";
?>
    <div class="w1100 bc pt10">
        <!--面包屑导航-->
        <p class="shopcart_step step3 none"></p>
        <?php if(Yii::$app->getSession()->getFlash('error')){?>
            <div class="login-msg  bc">
                <div class="error"><?php echo Yii::$app->getSession()->getFlash('error')?></div>
            </div>
        <?php }?>
        <!--购物信息 start-->
        <?php if($model->order){ ?>
        <dl class="graybg">
            <dt class="whitebg  pl30 clearfix  ">
            <div class="fl  pt10 pb10" style="width: 640px;">
                <h3 class="f14 fb">订单提交成功，请您尽快付款！ </h3>
                <p class="f12 gray6 lh25">请您在<span class="org">30分钟</span>内完成支付，否则订单会自动取消。</p>
            </div>
            <div class="fr pt10 pb10" style="width:190px;padding-right: 125px;">
                <p class="tr">应付总额 :<span class="org f18 fb lh180 "><?php echo number_format($model->total,2);?></span> 元</p>
            </div>
           </dt>
            <dd class="pl30 pb10">
            <?php foreach($model->order as $order){ ?>
              <p class="p5 lh200"> 订单号：<?php echo $order->order_no;?> &nbsp;&nbsp;&nbsp;&nbsp;订单总额：<?php echo number_format($order->total,2);?></p>
            <?php } ?>
            </dd>
        </dl>
        <?php } ?>
        <!--购物信息 end-->
        <!--支付平台 start-->
        <div class="order_mess">
        <form action="<?php echo yii\helpers\Url::to(["/payment/trade",'trade_no'=>$model->merge_code]); ?>" method="POST"  id="pay_order" >
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
            <?php $balance_status = false;?>
            <?php if($model->total==0){?>
            <div class="mb10">
                <label for="free-checkout"><input type="radio" class="vm mr5"  name="payment_method" value="free-checkout" id="free-checkout" checked="checked"/><img src="/assets/images/payment/free_checkout.gif" class="vm"  /></label>
            </div>
        <?php }else{ ?>
            <?php if(bccomp(Yii::$app->user->identity->balance,$model->total,2)>=0 && !$model->recharge_status){ ?>
                    <?php $balance_status = true;?>
            <div class=" mb10 ">
                <?php if(Yii::$app->user->identity->paymentpwd){?>
                <label><input type="radio"  id="payment_balance" name="payment_method" value="balance" class="mr5" /> <span class="fb f14">账户余额支付 <i class="red ">当前余额：￥<?=Yii::$app->user->identity->balance?></i></span></label>
                <div class="balance_gateway clearfix" style="display: none;" >
                     <input type="password" value="" class="ui-input i-text sixDigitPassword" name="payment_pwd" maxlength="6" minlength="6" style="outline: medium none;">
                   <p class="gray6 f14 mb10">请输入6位支付密码：</p>
                    <div class="sixDigitPassword fl" tabindex="0" style="width: 240px;">
                        <i class="" style="width: 39px; border-color: transparent;"><b style="visibility: hidden;"></b></i>
                        <i class="" style="width: 39px;"><b style="visibility: hidden;"></b></i>
                        <i class="" style="width: 39px;"><b style="visibility: hidden;"></b></i>
                        <i class="" style="width: 39px;"><b style="visibility: hidden;"></b></i>
                        <i class="" style="width: 39px;"><b style="visibility: hidden;"></b></i>
                        <i class="" style="width: 39px;"><b style="visibility: hidden;"></b></i>
                        <span style="width: 39px; visibility: hidden; left: 0px;"></span>
                    </div>
                    <a class="red fl mt5 ml10" href="<?=\yii\helpers\Url::to(['/security/security-update-paymentpwd','redirect'=>Yii::$app->request->getAbsoluteUrl()])?>">找回支付密码</a>
                </div>
                    <?php }else{?>
                    <label><span class="fb f14 mr30">账户余额支付</span>
                        * 首次使用余额支付，<a class="org " href="<?=\yii\helpers\Url::to(['/security/security-update-paymentpwd','redirect'=>Yii::$app->request->getAbsoluteUrl()])?>">请设置支付密码</a>
                        </label>
                <?php } ?>
           </div>
         <?php }elseif(Yii::$app->user->identity->balance > 0 && !$model->recharge_status){  ?>

            <dl class="payment">
                <dt><span class="fb f14 mr30">账户余额支付</span></dt>
                <dd>
                * 您需要再<a class="org" href="<?=\yii\helpers\Url::to(['/account-recharge/index',])?>">充值<?php echo ($model->total-Yii::$app->user->identity->balance)?></a> 元，才能启用余额支付；（余额支付不能跟其他在线支付混合使用）

                </dd>
</dl>

                <?php }?>
            <div class="payment_gateway">
                    <dl class="payment">
                        <dt><span class="fb f14 mr30">第三方支付平台</span> <span class="gray6">* 推荐使用银联支付，<i class="org">受信额度高，更安全。</i></span></dt>
                        <dd>
                            <ul class="clearfix">
                                <li style="border: none;width: auto;">
                                    <label for="alipay"><input type="radio" class="vm mr5"  name="payment_method" value="alipay" id="alipay" checked="checked" /><img src="/assets/images/payment/alipay.gif" class="vm"  /></label>
                                </li>
                                <?php
                                $can_use_cod = false;
                                $orders = $model->order;
                                if(count($orders) ==1){
                                    $order = $orders[0];
                                    $store = $order->store;
                                    if($store){
                                        if(($store->besupportpos == 1) && (Yii::$app->user->identity->can_use_cod==2) && !$model->recharge_status){
                                            $can_use_cod = true;
                                        }
                                    }
                                }
                                if($can_use_cod){ ?>
                                    <li style="border: none;width: auto;">
                                        <label for="cod"><input type="radio" class="vm mr5"  name="payment_method" value="cod" id="cod" /><img src="/assets/images/payment/cod.gif" class="vm"  /></label>
                                    </li>
                                <?php } ?>
                                <li style="border: none;width: auto;">
                                    <label for="upop"><input type="radio" class="vm mr5"  name="payment_method" value="upop" id="upop" /><img src="/assets/images/payment/upop.gif" class="vm"  /></label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl class="payment">
                        <dt><span class="fb f14 mr30">网银直接支付</span> <span class="gray6"> * 第三方无限额支付<i class="org">购物更便捷。</i>请选择银行卡种类！（例如：点击银行图标下面的储蓄卡或信用卡） </span></dt>
                        <dd >
                            <input type="hidden"   name="payment_method_type" value="1"/>
                            <ul class="clearfix">
                                <li>
                                    <span class="none"><input type="radio"  name="payment_method" value="allinpay_cmb"  /></span>
                                    <label><img src="/assets/images/payment/cmb.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"  name="payment_method" value="allinpay_icbc"  /></span>
                                    <label><img src="/assets/images/payment/icbc.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"  name="payment_method" value="allinpay_abc"  /></span>
                                    <label><img src="/assets/images/payment/abc.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"  name="payment_method" value="allinpay_ccb"  /></span>
                                    <label><img src="/assets/images/payment/ccb.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"  name="payment_method" value="allinpay_boc"  /></span>
                                    <label><img src="/assets/images/payment/boc.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"  name="payment_method" value="allinpay_spdb"  /></span>
                                    <label><img src="/assets/images/payment/spdb.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"  name="payment_method" value="allinpay_comm"  /></span>
                                    <label><img src="/assets/images/payment/comm.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"  name="payment_method" value="allinpay_cmbc"  /></span>
                                    <label><img src="/assets/images/payment/cmbc.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"  name="payment_method" value="allinpay_ceb"  /></span>
                                    <label><img src="/assets/images/payment/ceb.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"   name="payment_method" value="allinpay_citic"  /></span>
                                    <label><img src="/assets/images/payment/citic.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                   <span class="none"><input type="radio"   name="payment_method" value="allinpay_cib"  /></span>
                                    <label><img src="/assets/images/payment/cib.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"   name="payment_method" value="allinpay_pingan"  /></span>
                                    <label><img src="/assets/images/payment/pingan.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave">储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                                <li>
                                    <span class="none"><input type="radio"   name="payment_method" value="allinpay_psbc"  /></span>
                                    <label><img src="/assets/images/payment/psbc.gif" width="155" height="40" class="vm" /></label>
                                    <p class="save_credit clearfix">
                                        <span class="bysave" >储蓄卡</span>
                                        <span class="bycredit">信用卡</span>
                                    </p>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                </div>
            <?php } ?>
            <div>
                <a href="javascript:void (0);" class="btn btn_big orgBtn" id="submit_order"> 立即支付</a>
            </div>
        </form>
        <!--支付平台 end-->
    </div>
    </div>
    <!--支付弹出框-->
    <div class="payWin whitebg p20 pb50 w700" style="display:none;">
        <h2 class="f14 fb red bdb pb10 mb20">在网银页面完成支付前，请不要关闭本页面。</h2>
        <p class="ml100">
            <a href="javascript:void(0);" class="btn btn_middle grayBtn f14 fb mr30 chooseBack">重新选择支付方式</a>
            <a href="<?php echo \yii\helpers\Url::to(['checkout/complate'])?>" class="btn btn_middle greenBtn f14 fb mr30">已完成支付</a>
            <a href="/" class="btn btn_middle grayBtn f14 fb">返回首页</a>
        </p>
        <h2 class="f14 fb mb10 mt10">支付遇到问题？</h2>
        <p class="red">1、网银页面没有打开</p>
        <p>请检查您的浏览器设置，允许弹出窗口</p>
        <p class="red">2、网银支付失败</p>
        <p>您可以点击“重新选择支付方式”再次进行支付</p>
        <p class="red">3、网银支付成功但本页面没有跳转</p>
        <p>一般是网络原因造成的，请不必担心，您可以在商城查看订单记录确认支付结果。 </p>
    </div>


<?php $this->beginBlock('JS_END') ?>
//支付弹出框
$(document).ready(function(){
$("#submit_order").bind('click',function(){
var payment=$("input[name='payment_method']:checked").val();
var pwd=$("input[name='payment_pwd']").val();
if(payment){
if(payment=='balance' && pwd==""){
alert('请输入支付密码');
return false;
}
$('#pay_order').submit();
}else{
alert('请选择支付方式');
}
return false;
})
$(".chooseBack").click(function(e){
e.stopPropagation();
$(".payWin").hide();
$(".maskdiv").hide();
});
$(".save_credit span").click(function(){
$(this).parents("li").siblings().removeClass("cur");
$(this).parents("li").addClass("cur");
$(".save_credit span").removeClass("cur");
$(this).parents("li").find("input[name='payment_method']").trigger('click');
if($(this).attr("class")=='bysave'){
$("input[name='payment_method_type']").val(1);
}else{
$("input[name='payment_method_type']").val(11);
}
$(this).addClass("cur");
});
});
$(".sixDigitPassword").click(function(){
$(this).parents("dl").find("input[name='balance_status']").attr("checked",'true');
var len=$("input.sixDigitPassword").val().length;
$("div.sixDigitPassword").find("i").removeClass("active").eq(len).addClass("active");
//高亮框
$("div.sixDigitPassword").find("span").css("visibility","visible");
$("input.sixDigitPassword").focus().keyup(function(){
var len=$("input.sixDigitPassword").val().length;
$("div.sixDigitPassword").find("i").removeClass("active").eq(len).addClass("active");
(len>5) ? $("div.sixDigitPassword").find("span").animate({"left":5*40},170) : $("div.sixDigitPassword").find("span").stop().animate({"left":len*40},170);
for(e=0;e<len;e++){
$("div.sixDigitPassword").find("i").eq(e).find("b").css("visibility","visible");
}
for(e=len;e<7;e++){
$("div.sixDigitPassword").find("i").eq(e).find("b").css("visibility","hidden");
}
}).blur(function(){
var len=$("input.sixDigitPassword").val().length;
$("div.sixDigitPassword").find("i").removeClass("active").eq(len).removeClass("active");
$("div.sixDigitPassword").find("span").css("visibility","hidden");
});
});
$("body").on('click',"input[name='payment_method']",function(){
var a=$("input[name='payment_method']:checked").val();
if($(this).val()=='balance'){
$(".balance_gateway").show();
$(".sixDigitPassword").trigger('click');
}else{
$(".balance_gateway").hide();
}
if(a.indexOf("_")==-1){
$(".save_credit span").removeClass("cur");
$("dd li").removeClass("cur");
}
});
if($("input[name='payment_method']:checked").val()=='balance'){
$("input[name='payment_method']:checked").trigger('click');
}

<?php if($balance_status){ ?>
   $("#payment_balance").trigger('click');
  
<?php }?>

<?php $this->endBlock() ?>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
$this->registerCssFile("/assets/stylesheets/plugin/sixDigitPassword.css",['depends'=>[\frontend\assets\AppAsset::className()]]);
?>
