<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:57
 */
?>
<div class="site-nav">
    <div class="site-nav-bd clearfix" style="width: 1100px">
        <ul class="site-nav-bd-l clearfix" >
            <li><a href="/">家润首页</a></li>
            <?php if(Yii::$app->user->isGuest){?>
                <li><a href="<?=\yii\helpers\Url::to('/site/login')?>">请登录</a></li>
                <li><a href="<?=\yii\helpers\Url::to('/site/signup')?>">免费注册</a></li>
            <?php }else{?>
                <li><a href="<?=\yii\helpers\Url::to('/account')?>"><?php echo Yii::$app->user->getIdentity()->username?></a></li>
                <li><a href="<?=\yii\helpers\Url::to('/site/logout')?>">[退出]</a></li>
            <?php }?>
        </ul>

        <ul class="site-nav-bd-r clearfix">
            <li class="sn-menu">
                <a class="menu-hd" href="<?php echo \yii\helpers\Url::to('/message/index');?>" rel="nofollow"><i class="iconfont green f14">&#xe629;</i> 消息 <span class="green"><?=Yii::$app->user->isGuest?0:Yii::$app->user->identity->messageCount?></span><b></b></a>
                <div class="menu-bd">
                    <a href="<?php echo \yii\helpers\Url::to('/message/index');?>" rel="nofollow">查看全部</a>
                </div>
            </li>
            <li class="sn-menu">
                <a href="<?=\yii\helpers\Url::to('/account')?>" class="menu-hd" rel="nofollow">我的家润<b></b></a>
                <div class="menu-bd">
                    <a href="<?=\yii\helpers\Url::to('/order/index')?>" rel="nofollow">我的订单</a>
                    <a href="<?=\yii\helpers\Url::to('/collect/index')?>" rel="nofollow">我的收藏</a>
                    <!-- <a rel="nofollow">我的消息</a> -->
                </div>
            </li>
            <li><a href="<?=\yii\helpers\Url::to('/cart/index')?>">购物车</a></li>
            <li class="sn-mobile">
                APP下载
                <div class="sn-qrcode">
                    <img src="/assets/images/app.png" alt="每日惠购-微博二维码" width="120" height="120" class="db">
                    <b></b>
                </div>
            </li>
            <li class="sn-mobile">
                微信
                <div class="sn-qrcode">
                    <img src="/assets/images/wx.jpg" alt="每日惠购-微信二维码" width="120" height="120" class="db">
                    <b></b>
                </div>
            </li>
            <li class="sn-mobile">
                微博
                <div class="sn-qrcode">
                    <img src="/assets/images/weibo.png" alt="每日惠购-微博二维码" width="120" height="120" class="db">
                    <b></b>
                </div>
            </li>
            <li><a href="javascript:void(0)" onclick="addToFav('http://www.mrhuigou.com','每日惠购')">收藏网站</a></li>
        </ul>
    </div>
</div>