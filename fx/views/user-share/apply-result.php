<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/5/8
 * Time: 14:30
 */
$this->title="提现结果";
?>
<?=fx\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  pb50">
	<div class="tc  ">
		<i class="iconfont fb green f50">&#xe61a;</i>
		<h1 class="fb f20 lh200 green">提现已提交</h1>
		<p class=" f14 fb lh200">亲，需3到5个工作日</p>
		<p class=" f14 fb lh200">提现金额将自动转入您的微信钱包</p>
		<div class="m10"> <a class="btn lbtn greenbtn w" href="<?php echo \yii\helpers\Url::to(['/user/index'])?>">返回</a></div>
	</div>
</section>
<!--浮动购物车-->
<?=fx\widgets\MainMenu::widget();?>
