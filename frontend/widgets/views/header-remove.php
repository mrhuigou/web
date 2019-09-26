
<!--top-->

<?=\frontend\widgets\HeaderTopAd::widget()?>
<!--logo和search-->
<div class="w1200 bc">
    <!--logo 和搜索区-->
    <div class="clearfix w1200 bc">
        <a href="<?php echo \yii\helpers\Url::to(['site/index'])?>" class="fl">
            <img src="../assets/images/new/logo.jpg" alt="logo"  class="db">
        </a>
        <div class="fr" style="padding-top:70px;">
            <div class="gray6 f18 pr50">
                <a class="pl10 pr10 share_pop" href="javascript:void(0)">
                    <img class="mr5 vm_3" src="../assets/images/new/gift1.jpg" />新会员礼包
                </a> |
                <a class="pl10 pr10" href="https://www.mrhuigou.com/information/9.html">配送范围</a> |
                <a class="pl10 pr10" href="https://www.mrhuigou.com/information/208.html">配送时效</a> |
                <a class="pl10 pr10" href="https://www.mrhuigou.com/information/merchant">开放平台</a> |
                <a class="pl10 pr10" href="javascript:void(0)" onclick="Mibew.Objects.ChatPopups['58393869600fdc8b'].open();return false;">在线客服</a>
            </div>
        </div>
    </div>
</div>
<div class="newGift-con" style="display: none;">
    <div class="tc p30">
        <img src="../assets/images/new/share-logo.png" alt="这里是二维码" title="点击扫描二维码1">
    </div>
</div>
<script type="text/javascript" src="https://im.mrhuigou.com/js/compiled/chat_popup.js"></script>
<script type="text/javascript">Mibew.ChatPopup.init({"id":"58393869600fdc8b","url":"https:\/\/im.mrhuigou.com\/chat?locale=zh-cn","preferIFrame":false,"modSecurity":false,"width":640,"height":480,"resizable":true,"styleLoader":"https:\/\/im.mrhuigou.com\/chat\/style\/popup"});</script><!-- / mibew text link -->

<?php $this->beginBlock('JS_END') ?>


// 新会员礼包点击
$(".share_pop").click(function(){

layer.open({
type: 1,
closeBtn: 2,
title: false,
shadeClose: true,
content: $('.newGift-con').html()
//btn: ['确认']
});
})
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
