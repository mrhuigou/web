<!--主导航-->
<div class="fx-bottom fx-convert-tar">
    <div class="nav-main clearfix">
        <a href="<?=\yii\helpers\Url::to(['/site/index'])?>" class="<?=$cur=="home"?"green fb":""?>"><i class="iconfont">&#xe63f;</i>首页</a>
<!--        <a href="--><?php //echo \yii\helpers\Url::to(['/category/index'])?><!--" class="--><?//=$cur=="category"?"green fb":""?><!--"><i class="iconfont">&#xe640;</i>分类</a>-->
         <a href="<?php echo \yii\helpers\Url::to(['/coupon/index'])?>" class="<?=$cur=="coupon"?"green fb":""?>"><i class="iconfont">&#xe640;</i>领券</a>

<!--        <a href="--><?//=\yii\helpers\Url::to(['/choujiang/index'])?><!--"><i class="" style="margin-top:-19px;"><img src="/assets/images/fudai.png" width="30" class="shakesmall"></i><span class="f12 db org">抽奖</span></a>-->
        <a href="<?=\yii\helpers\Url::to(['page/3538.html'])?>" class="<?=$cur=="page"?"green fb":""?>"><i class="" style="margin-top:-19px;"><img src="http://img1.mrhuigou.com/group1/M00/06/BB/wKgB7l5d0Z2AOoKQAAA8c-N7rkI687.gif" width="30" class="shakesmall"></i><span class="f12 db org">爆品</span></a>
        <a href="<?=\yii\helpers\Url::to(['/cart/index'])?>" class="pr <?=$cur=="cart"?"green fb":""?>">
            <i class="iconfont">&#xe63b;</i>购物车
            <?php if(Yii::$app->cart->getCount()){ ?><em class="info-point ellipsis cart_qty pa-rt r5" style="max-width: 22px;"><?=Yii::$app->cart->getCount()?></em><?php }else{ ?>
                <em class="info-point ellipsis cart_qty pa-rt r5" style="display:none;max-width: 22px;"><?=Yii::$app->cart->getCount()?></em>
            <?php } ?>
        </a>
        <a href="<?=\yii\helpers\Url::to(['/user/index'])?>" class="<?=$cur=="user"?"green fb":""?>"><i class="iconfont">&#xe603;</i>我的</a>
    </div>
</div>