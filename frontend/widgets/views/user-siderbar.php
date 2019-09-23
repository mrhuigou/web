<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/3
 * Time: 12:01
 */?>
<dl class="menu-tree-item">
    <dt>订单中心</dt>
    <dd>
        <a href="<?=\yii\helpers\Url::to(['/order/index'],true) ?>">我的订单</a>
    </dd>
    <dd>
        <a href="<?=\yii\helpers\Url::to(['/user-coupon/index'],true) ?>">优惠券</a>
    </dd>
    <dd>
        <a href="<?=\yii\helpers\Url::to(['/account-recharge'],true) ?>">帐户充值</a>
    </dd>
    <dd>
        <a href="<?=\yii\helpers\Url::to(['/order/return'],true) ?>">退货订单</a>
    </dd>
    <dd>
        <a href="<?=\yii\helpers\Url::to(['/order/review'],true) ?>">商品评论</a>
    </dd>
</dl>
<dl class="menu-tree-item">
    <dt>账户中心</dt>
    <dd>
        <a href="<?=\yii\helpers\Url::to(['/address'],true) ?>">地址管理</a>
    </dd>
    <dd>
        <a href="<?=\yii\helpers\Url::to(['/profile'],true) ?>">个人资料</a>
    </dd>
    <dd>
        <a href="<?=\yii\helpers\Url::to(['/collect'],true) ?>">我的收藏</a>
    </dd>
    <dd>
        <a href="<?=\yii\helpers\Url::to(['/security'],true) ?>">账户安全</a>
    </dd>
</dl>

<!-- <dl class="menu-tree-item">
    <dt>消息</dt>
    <dd>
        <a href="#">订阅设置</a>
    </dd>
</dl> -->
