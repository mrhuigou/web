<?php
?>
<div id="header" style="margin-top:0!important;">
    <!--top-->
    <?=\frontend\widgets\HeaderTop::widget()?>
    <!--logo和search-->
    <div class="header-box w1100 bc">
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
    </div>
</div>