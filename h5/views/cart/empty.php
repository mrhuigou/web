<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='购物车';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport">
    <div class=" pb50">
    <figure class="info-tips  gray9 p10">
        <i class="iconfont ">&#xe67a;</i>
        <figcaption class="m10"> 购物车空空如也，快去购物吧</figcaption>
    </figure>
    <div class="w tc p5">
        <a class="btn mbtn redbtn " href="/">去逛逛</a>
        <a class="graybtn btn mbtn  " href="<?php echo \yii\helpers\Url::to(['/user/index'])?>">用户中心</a>
    </div>
        <?=\h5\widgets\Cart\Relation::widget()?>
    </div>
</section>
<?= h5\widgets\MainMenu::widget(); ?>