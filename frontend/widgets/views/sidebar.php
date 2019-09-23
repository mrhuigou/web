<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/12/21
 * Time: 15:40
 */?>
<!--侧边栏浮动条-->
<div class="right-float" style="z-index: 1000">
    <div class="right-float-main">
        <a href="<?php echo yii\helpers\Url::to(["/cart/index"]);?>"  class="gouwuche shopcart_num cart_fly"><span class="iconfont">&#xe606;</span><span class="db f12 simSun">购物车</span><i class="numicon"><?php echo count(Yii::$app->cart->getPositions());?></i></a>
        <a href="https://im.365jiarun.com/chat?locale=zh-cn" target="_blank" onclick="Mibew.Objects.ChatPopups['58393869600fdc8b'].open();return false;" title="联系客服"><i class="iconfont">&#xe611;</i></a>
        <a href="/collect/index" class="" title="我的收藏"><i class="iconfont">&#xe62a;</i></a>
        <a href="/account/index" class="" title="我的订单"><i class="iconfont">&#xe622;</i></a>
        <a href="/message/index" class=" pr" title="我的消息"><i class="iconfont">&#xe629;</i><i class="numicon pa" style="top: -1px;right: 0px;"> <?=Yii::$app->user->isGuest?0:Yii::$app->user->identity->messageCount?></i></a>
    </div>
    <div class="right-float-bottom greenbg">
        <a href="javascript:void(0)"  title="用户反馈"><i class="iconfont">&#xe62c;</i></a>
        <a href="javascript:void(0)" class="back-top backTop" title="返回顶部"></a>
    </div>
</div>
<script type="text/javascript" src="https://im.365jiarun.com/js/compiled/chat_popup.js"></script>
<script type="text/javascript">Mibew.ChatPopup.init({"id":"58393869600fdc8b","url":"https:\/\/im.365jiarun.com\/chat?locale=zh-cn","preferIFrame":false,"modSecurity":false,"width":640,"height":480,"resizable":true,"styleLoader":"https:\/\/im.365jiarun.com\/chat\/style\/popup"});</script><!-- / mibew text link -->

