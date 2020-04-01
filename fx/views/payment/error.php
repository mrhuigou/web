<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = "支付错误";
?>
<?=fx\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  mb50">
    <figure class="info-tips red">
        <i class="iconfont font-warning ">&#xe616;</i>
        <figcaption class="mt10"> <?= nl2br(Html::encode($message)) ?></figcaption>
    </figure>
    <div class="white">
    <a class="btn mbtn db mt10 orgbg w br5"  href="javascript:history.back();">返回</a>
    </div>
</section>
<?= h5\widgets\MainMenu::widget(); ?>