<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='支付完成';
?>
<div class=" graybg " style="min-width:1200px;">
    <div class="w1100 bc pt10">
        <!--面包屑导航-->
        <p class="shopcart_step step4"></p>
        <p class="f20 green tc p10 "><i class="vm">您的订单已成功提交!</i></p>
        <p class="red f18 tc pb10">关注领红包</p>
        <p class="tc">
            <img src="/assets/images/wx.jpg" alt="二维码" width="200" height="200">
        </p>
        <p class="gray9  tc">微信扫描二维码，关注家润公众号</p>
        <div class="p10 tc ">
            <a class="btn sbtn greenbtn " href="<?=\yii\helpers\Url::to(['/order/index'])?>">查看我的订单中心</a>
        </div>
    </div>
</div>