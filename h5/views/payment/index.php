<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use \common\component\Helper\Helper;
$this->title = "收银台";
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  mb50">
    <div class="fool white clearfix mb10">
        <em class="fl mr15 iconfont">&#xe61f;</em>
        <div class="fl w-per77 f12 lh150">
            <p class="f14 mb5">请在30分钟内完成付款，逾期系统自动取消。</p>
            <p>交易号:<?=$model->merge_code?></p>
            <p>应付金额:￥<?=number_format($model->total,2,'.','')?></p>
        </div>
    </div>
    <div class="line"></div>

    <div class="mt10 p10">
        <?php if($model->total>0){ ?>
            <?php if(strpos(strtolower(\Yii::$app->request->getUserAgent()), 'micromessenger')){  ?>

                <a href="javascript:void()" id="weixin_btn" onclick="jsApiCall()"  class="payment clearfix">
                    <span class="img wechat"></span>
                    <div class="fl">
                        <h3>微信安全支付</h3>
                        <span class="gray9 f12">安全、方便、快捷</span>
                    </div>
                    <i class="iconfont fr"></i>
                </a>
        <?php }else{ ?>
                <a href="javascript:void()" id="weixin_wap_btn"   class="payment clearfix">
                    <span class="img wechat"></span>
                    <div class="fl">
                        <h3>微信安全支付</h3>
                        <span class="gray9 f12">安全、方便、快捷</span>
                    </div>
                    <i class="iconfont fr"></i>
                </a>
        <?php }?>
        <?php if(strpos(strtolower(\Yii::$app->request->getUserAgent()), 'micromessenger') === false){ ?>
<!--            <a href="--><?php //echo \yii\helpers\Url::to(['/payment/alipay','trade_no'=>$model->merge_code])?><!--"  class="payment clearfix">-->
<!--            <span class="img zfb"></span>-->
<!--            <div class="fl">-->
<!--                <h3>支付宝支付</h3>-->
<!--                <span class="gray9 f12">安全、方便、快捷</span>-->
<!--            </div>-->
<!--            <i class="iconfont fr"></i>-->
<!--        </a>-->
            <?php  } ?>
<!--        <a href="--><?php //echo\yii\helpers\Url::to(['/payment/upop-pay','trade_no'=>$model->merge_code])?><!--"  class="payment clearfix">-->
<!--            <span class="img yl"></span>-->
<!--            <div class="fl">-->
<!--                <h3>银联支付</h3>-->
<!--                <span class="gray9 f12">安全、方便、支持200多家银行</span>-->
<!--            </div>-->
<!--            <i class="iconfont fr"></i>-->
<!--        </a>-->
        <?php
//        if(!$model->recharge_status){ ?>
<!--            <a id="BestoneBtn" href="javascript:void(0)"  class="payment clearfix">-->
<!--                <span class="img bestone"></span>-->
<!--                <div class="fl">-->
<!--                    <h3>佰通卡支付</h3>-->
<!--                    <span class="gray9 f12 red">开卡时已提供发票,家润网不再提供发票</span>-->
<!--                </div>-->
<!--                <i class="iconfont fr"></i>-->
<!--            </a>-->
<!--            --><?php //}?>
       <?php if(bccomp(Yii::$app->user->identity->balance,$model->total,2)>=0 && !$model->recharge_status){?>
        <a href="<?=\yii\helpers\Url::to(['/payment/balance','trade_no'=>$model->merge_code])?>"  class="payment clearfix">
            <span class="img ye"></span>
            <div class="fl">
                <h3>账户余额</h3>
                <span class="gray9 f12">当前：￥<?=Yii::$app->user->identity->balance?></span>
            </div>
            <?php if(Yii::$app->user->identity->paymentpwd){ ?>
               <i class="iconfont fr"></i>
               <?php }else{ ?>
               <i class="iconfont fr"><em class="f12 p10 gray9">设置支付密码</em></i>
            <?php } ?>
</a>
            <?php }elseif(Yii::$app->user->identity->balance >= 0 && !$model->recharge_status){  ?>
            <a href="<?=\yii\helpers\Url::to(['/account-recharge/index'])?>"  class="payment clearfix">
                <span class="img ye"></span>
                <div class="fl">
                    <h3>余额支付</h3>
                    <span class="gray9 f12">当前：￥<?=Yii::$app->user->identity->balance?></span>
                </div>
            </a>
        <?php } ?>
            <?php
            $can_use_cod = false;
            $orders = $model->order;
            if(count($orders) ==1){
                $order = $orders[0];
                $store = $order->store;
                if($store){
                    if(($store->besupportpos == 1) && (Yii::$app->user->identity->can_use_cod) && !$model->recharge_status){
                        $can_use_cod = true;
                    }
                }
            }
            if($can_use_cod){ ?>
                <a href="<?=\yii\helpers\Url::to(['/payment/cod','trade_no'=>$model->merge_code])?>"  class="payment clearfix">
                    <span class="img cod"></span>
                    <div class="fl">
                        <h3>货到卡付</h3>
                        <span class="gray9 f12">仅开放给某些特权用户</span>
                    </div>
                </a>
            <?php } ?>
        <?php }else{?>
        <div class="white">
            <a href="<?=\yii\helpers\Url::to(['/payment/free-pay','trade_no'=>$model->merge_code])?>"  class="btn mbtn mt10 w white greenbg">免费支付</a>
         </div>
        <?php } ?>
    </div>
</section>
<?= h5\widgets\MainMenu::widget(); ?>
<script>
<?php $this->beginBlock('JS_END') ?>
<?php if(strpos(strtolower(\Yii::$app->request->getUserAgent()), 'micromessenger')){  ?>
function jsApiCall()
{
    var obj=$("#weixin_btn");
    obj.attr('disabled',true);
//    obj.removeClass('greenbtn');
//    obj.addClass("graybtn");
//    obj.html("正在努力加载，请稍等...")
    $.showLoading("正在提交");
    $.get('<?=\yii\helpers\Url::to(['/payment/wxpay','trade_no'=>$model->merge_code])?>',function(data){
        $.hideLoading();
        if(data.status){
            WeixinJSBridge.invoke('getBrandWCPayRequest',jQuery.parseJSON(data.data),function(res){
                if(res.err_msg=="get_brand_wcpay_request:ok"){
                    location.href='<?=\yii\helpers\Url::to(['checkout/complate','trade_no'=>$model->merge_code],true)?>';
                }else{
                    obj.removeAttr('disabled');
//                    obj.html("微信安全支付")
//                    obj.removeClass('graybtn');
//                    obj.addClass("greenbtn");
                }
            });
        }else{
            alert(data.message);
            obj.removeAttr('disabled');
//            obj.html("微信安全支付")
//            obj.removeClass('graybtn');
//            obj.addClass("greenbtn");
        }
    },'json');
}
<?php  } ?>
$("#weixin_wap_btn").bind("click",function(){
    $.showLoading("正在提交");
    var ua = navigator.userAgent.toLowerCase();
    $.get('<?=\yii\helpers\Url::to(['/payment/wxpay','trade_no'=>$model->merge_code])?>',function(res){
        $.hideLoading();
        if(res.status){
            $.cookie('h5_wx_cookie', '<?=$model->merge_code?>', { expires: 1 });
//            $.modal({
//                title: "支付提示",
//                text: "请确认微信支付是否完成",
//                buttons: [
//                    { text: "重新支付", onClick: function(){
//                        $.cookie('h5_wx_cookie', null);
//                        location.reload(true);} },
//                    { text: "已完成支付", onClick: function(){
//                        $.cookie('h5_wx_cookie', null);
//                        location.reload(true); } }
//                ]
//            });
            if (/iphone|ipad|ipod/.test(ua)) {
                location.href=res.data+'&redirect_url=<?=urlencode('https://m.mrhuigou.com/site/index?trade_no='.$model->merge_code)?>';
            } else{
                location.href=res.data;
            }
        }else{
            $.alert(res.message);
        }
    },'json');
})
if($.cookie('h5_wx_cookie') && $.cookie('h5_wx_cookie')=='<?=$model->merge_code?>'){
    $.modal({
        title: "支付提示",
        text: "请确认微信支付是否完成",
        buttons: [
            { text: "重新支付", onClick: function(){
                $.cookie('h5_wx_cookie', null);
                location.reload(true);} },
            { text: "已完成支付", onClick: function(){
                $.cookie('h5_wx_cookie', null);
                location.reload(true); } }
        ]
    });
}
$("#BestoneBtn").bind("click",function(){
    $.modal({
        title: "支付提示",
        text: "您购买的佰通卡已经开具发票，家润网不再提供发票",
        buttons: [
            { text: "取消", onClick: function(){

            } },
            { text: "确定", onClick: function(){
                    location.href= "<?=\yii\helpers\Url::to(['/payment/bestonepay', 'trade_no' => $model->merge_code],true)?>";
                }
            }
        ]
    });
});

<?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
<?=\h5\widgets\Block\Start::widget(['type'=>1]);?>