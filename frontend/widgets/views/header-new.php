
<!--top-->
<?php if ($this->beginCache('frontend-header-top-new')) { ?>
<?=$this->renderDynamic('return \frontend\widgets\HeaderNewTop::widget();');?>
<?php $this->endCache(); }?>
<?=\frontend\widgets\HeaderTopAd::widget()?>
<!--logo和search-->
<div class="w1200 bc">
    <!--logo 和搜索区-->
    <div class="clearfix pt15 pb5">
        <a href="<?=\yii\helpers\Url::to('/site/index')?>" class="fl mt5 ">
            <img src="/assets/images/v3_logo.png" alt="logo" width="201" height="46" class="db">
        </a>
        <div class="fl">
            <?=frontend\widgets\SearchBar::widget()?>
        </div>

        <a href="javascript:void(0)" class="fr">
            <img src="/assets/images/carton7.10.gif" alt="68元包邮" width="215" height="67" class="db">
        </a>
    </div>
    <!--主导航-->
    <div class="nav_bx clearfix">
        <div class="allcate fl">
            <h1><i class="iconfont"></i>全部产品分类 </h1>
        </div>
        <div class="w600 fl navbar clearfix">

            <a href="<?php echo \yii\helpers\Url::to(['club/try'])?>" class="cur">免费试</a>
            <a href="<?php echo \yii\helpers\Url::to(['club/default'])?>">生活圈</a>
            <a href="/promotion/NEW.html">新品来袭</a>
            <a href="/promotion/PANIC.html">闪购</a>
        </div>
    </div>
</div>
