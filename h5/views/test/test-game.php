<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '下单后抽奖测试';
$this->params['breadcrumbs'][] = $this->title;
?>
<header class="header w" id="header">
<!--    <a href="javascript:history.back();" class="his-back">返回</a>-->
    <h2><?= Html::encode($this->title) ?></h2>
<!--    <a href="/site/signup" class="right">注册</a>-->
</header>
<section class="veiwport">
    <?= h5\widgets\Block\Game::widget(['customer_id' => $customer_id]) ?>
</section>
