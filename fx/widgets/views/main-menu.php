<!--主导航-->
<div class="fx-bottom fx-convert-tar">
    <div class="nav-main clearfix">
        <a href="<?=\yii\helpers\Url::to(['/site/index'])?>" class="<?=$cur=="home"?"green fb":""?>"><i class="iconfont">&#xe63f;</i>首页</a>
        <a href="<?=\yii\helpers\Url::to(['/cart/index'])?>" class="pr <?=$cur=="cart"?"green fb":""?>">
            <i class="iconfont">&#xe63b;</i>购物车
            <?php if(Yii::$app->fxcart->getCount()){ ?><em class="info-point ellipsis cart_qty pa-rt r5" style="max-width: 22px;"><?=Yii::$app->fxcart->getCount()?></em><?php }else{ ?>
                <em class="info-point ellipsis cart_qty pa-rt r5" style="display:none;max-width: 22px;"><?=Yii::$app->fxcart->getCount()?></em>
            <?php } ?>
        </a>
        <a href="<?=\yii\helpers\Url::to(['/user/index'])?>" class="<?=$cur=="user"?"green fb":""?>"><i class="iconfont">&#xe603;</i>我的</a>
    </div>
</div>