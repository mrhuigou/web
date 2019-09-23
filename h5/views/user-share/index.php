<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/8
 * Time: 11:55
 */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='我的分享';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  pb50">
	<div class="flex-col tc lh37 aui-border whitebg f14  ">
		<a class="flex-item-4 redbg white" href="<?=\yii\helpers\Url::to(['/user-share/index'])?>">
			收益
		</a>
        <a class="flex-item-4  aui-border-l " href="<?=\yii\helpers\Url::to(['/user-share/follower'])?>">
            粉丝
        </a>
		<a class="flex-item-4 aui-border-l " href="<?=\yii\helpers\Url::to(['/user-share/commission'])?>">
			明细
		</a>
	</div>
	<div class="mt5  whitebg p10 fb ">概况</div>
	<div class="flex-col flex-center tc whitebg bdt bdb  p10">
		<div class="flex-item-6  flex-middle  p10 bdr bdb " >
			<p>当天收入（元）</p>
			<p class="red"><?=$today_total?></p>
		</div>
		<div class="flex-item-6  flex-middle  bdb p10" >
			<p>当周收入（元）</p>
			<p class="red"><?=$week_total?></p>
		</div>
		<div class="flex-item-6  flex-middle bdr  p10" >
			<p>当月收入（元）</p>
			<p class="red"><?=$month_total?></p>
		</div>
		<div class="flex-item-6  flex-middle    p10" >
			<p>累计收入（元）</p>
			<p class="red"><?=$history_total?></p>
		</div>
	</div>
    <div class="m10">
        <p class="f14 fb tc p10 lh200">可提现收益：<span class="red"><?=floatval(Yii::$app->user->identity->getCommission())?></span>元</p>
        <p class="lh150 f5 f12 tc none">我的收益 <?=floatval(Yii::$app->user->identity->getCommission())?> 元</p>
    </div>
    <div class="m5">
        <a class="btn lbtn greenbtn w " href="/user-share/apply-draw" >申请提现</a>
    </div>
    <div class="m5">
        <a class="btn lbtn graybtn w " href="<?=\yii\helpers\Url::to(['/user-share/order'])?>">粉丝订单收益</a>
    </div>
    <div class="m5">
        <a class="btn lbtn graybtn w " href="<?=\yii\helpers\Url::to(['/user-share/order-aff-person'])?>">分享商品收益</a>
    </div>

    <div class="p5 m5  lh150 bg-wh f12 f5">
        友情提示：亲，您推广的用户订单收益，系统会在订单交易完成后，自动转入你的收益帐户中。
    </div>
</section>
	<!--浮动购物车-->
<?=h5\widgets\MainMenu::widget();?>

