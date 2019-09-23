<!--主导航-->
<nav class="fx-bottom fx-convert-tar">
    <div class="nav-main clearfix">
        <a href="<?=\yii\helpers\Url::to(['/shop/index','shop_code'=>$code])?>"><i class="iconfont">&#xe63f;</i>首页</a>
        <a href="<?=\yii\helpers\Url::to(['/shop/category','shop_code'=>$code])?>"><i class="iconfont">&#xe640;</i>分类</a>
        <a href="<?=\yii\helpers\Url::to(['/shop/index','shop_code'=>$code])?>"><i class="iconfont">&#xe659;</i>分享有礼</a>
        <a href="<?=\yii\helpers\Url::to(['/cart/index'])?>" class="pr ">
            <i class="iconfont">&#xe63b;</i>购物车
            <?php if(Yii::$app->cart->getCount()){ ?><em class="info-point cart_qty"><?=Yii::$app->cart->getCount()?></em><?php }else{ ?>
                <em class="info-point cart_qty" style="display:none"><?=Yii::$app->cart->getCount()?></em>
            <?php } ?>
        </a>
        <a href="<?=\yii\helpers\Url::to(['/user/index'])?>"><i class="iconfont">&#xe603;</i>我的</a>
    </div>
</nav>