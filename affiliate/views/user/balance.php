<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
$this->title = '我的余额';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  mb50">
		<div class="tc pt50">
			<i class="iconfont green" style="font-size: 70px;">&#xe65b;</i>
			<p class="f18 pt30">我的余额</p>
			<p class="f30 pt20">￥<?= Yii::$app->user->getIdentity()->balance; ?></p>
		</div>
		<div class="p15">
			<a href="<?php echo \yii\helpers\Url::to(['/account-recharge'])?>" class="btn mbtn w greenbtn mt10">充值</a>
			<a href="<?php echo \yii\helpers\Url::to(['/user/balance-list'])?>" class="btn mbtn w graybtn mt10">查看明细</a>
		</div>
</section>
<?= h5\widgets\MainMenu::widget(); ?>
