<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:57
 */
?>

<div class="site-nav">
    <div class="site-nav-bd w1200 clearfix">
        <ul class="site-nav-bd-l clearfix">
            <li>
                <a href="/">
                    <i class="iconfont green">&#xe610;</i>
                    家润首页
                </a>
            </li>
            <?php if(Yii::$app->user->isGuest){?>
                <li><a href="<?=\yii\helpers\Url::to('/site/login')?>"><span class="cp">请登录</span></a></li>
                <li><a href="<?=\yii\helpers\Url::to('/site/signup')?>"><span class="cp">免费注册<span></span></a></li>
            <?php }else{?>
                <li><a href="<?=\yii\helpers\Url::to('/account')?>"><span class="cp"><?php echo Yii::$app->user->getIdentity()->username?></span></a></li>
                <li><a href="<?=\yii\helpers\Url::to('/site/logout')?>"><span class="cp">[退出]</span></a></li>
            <?php }?>
        </ul>

        <ul class="site-nav-bd-r clearfix">
            <li class="sn-menu">
                <a class="menu-hd" href="<?php echo \yii\helpers\Url::to('/message/index');?>" rel="nofollow"><i class="iconfont green f14">&#xe629;</i> 消息 <span class="green"><?=Yii::$app->user->isGuest?0:Yii::$app->user->identity->messageCount?></span><b></b></a>
                <div class="menu-bd">
                    <a href="<?php echo \yii\helpers\Url::to(['/message/index'])?>" rel="nofollow">查看全部</a>
                </div>
            </li>
            <li class="sn-menu">
                <a class="menu-hd" href="<?=\yii\helpers\Url::to('/account')?>" rel="nofollow">我的家润<b></b></a>
                <div class="menu-bd">
                    <a href="<?=\yii\helpers\Url::to('/order/index')?>" rel="nofollow">我的订单</a>
                    <a href="<?=\yii\helpers\Url::to('/collect/index')?>" rel="nofollow">我的收藏</a>
                </div>
            </li>
            <li><a href="<?=\yii\helpers\Url::to('/cart/index')?>"><i class="iconfont green f14">&#xe612;</i> 购物车(<?php echo Yii::$app->cart->getCount();?>)</a></li>
            <li class="sn-mobile">
                <i class="iconfont green f14">&#xe613;</i> APP下载
                <div class="sn-qrcode">
                    <img src="/assets/images/app.png" alt="家润慧生活" width="120" height="120" class="db">
                    <b></b>
                </div>
            </li>
            <li class="sn-mobile"><a href="#"><i class="iconfont green f14">&#xe60e;</i> 微信</a>
                <div class="sn-qrcode">
                    <img src="/assets/images/wx.jpg" alt="家润慧生活" width="120" height="120" class="db">
                    <b></b>
                </div>
            </li>
            <li class="sn-mobile"><a href="#"><i class="iconfont green f14">&#xe60f;</i> 微博</a>
                <div class="sn-qrcode">
                    <img src="/assets/images/weibo.png" alt="家润慧生活" width="120" height="120" class="db">
                    <b></b>
                </div>
            </li>
            <li><a href="javascript:void(0)" onclick="addToFav('http://www.365jiarun.com','家润慧生活')"><i class="iconfont green f14">&#xe614;</i> 收藏夹</a></li>
        </ul>
    </div>
</div>

