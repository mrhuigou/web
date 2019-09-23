<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:58
 */
?>
<div id="header" style="margin-top:0!important;">
    <!--top-->
    <?=\frontend\widgets\HeaderTop::widget()?>
    <!--logo和search-->
    <div class="header-wrap">
        <div class="header-box w1100 bc">
            <div class="logo-search clearfix">
                <div class="logos fl">
                    <a href="/"><img src="/assets/images/logo1.png" width="153" alt="慧生活logo" /></a>
                </div>
                <ul class="jr-nav fl clearfix">
                    <li><a href="<?=yii\helpers\Url::to(["/site/index"])?>">首页</a></li>
                    <li><a href="<?=yii\helpers\Url::to(["/order/index"])?>">订单中心</a></li>
                    <li><a href="<?=yii\helpers\Url::to(["/account/index"])?>">帐户中心</a></li>
                    <li><a href="<?=yii\helpers\Url::to(["/club/default/index"])?>">生活圈</a></li>
                </ul>
                <div class="search-box fr pr clearfix" >
                    <form action="<?php echo yii\helpers\Url::to(["/category/index"])?>" method="get" id="search_form">
                        <input type="text" id="search_keyword" name="keyword"  value="<?=\yii\helpers\Html::encode(Yii::$app->request->get('keyword'))?>" placeholder="搜索你想要的商品"  class="fl">
                        <button class="btn-search fr search-btn" >搜索</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>






