<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = "系统繁忙";
?>

<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
    <a href="<?php echo \yii\helpers\Url::to(['/site/index'])?>" class="header-cate iconfont">&#xe602;</a>
</header>
<section class="veiwport">
    <figure class="info-tips">
        <i class="iconfont font-404 ">&#xe67a;</i>
        <figcaption class="mt10"> <?= nl2br(Html::encode($message)) ?></figcaption>
        <figcaption class="p10"> <a href="<?php echo \yii\helpers\Url::to(['/site/index'])?>" class="btn mbtn redbtn">返回首页</a> <a href="<?=Yii::$app->request->getAbsoluteUrl()?>" class="btn mbtn graybtn">刷新重试</a></figcaption>
    </figure>
</section>
