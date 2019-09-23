<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/8
 * Time: 11:55
 */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='等待审核';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  pb50">
	<div class="tc  ">
		<i class="iconfont fb green f50">&#xe61a;</i>
		<h1 class="fb f20 lh200 green">提交成功，等待审核</h1>
		<p class=" f18 fb lh200">亲，3到5个工作日出审核结果</p>
	</div>
</section>
<!--浮动购物车-->
<?=h5\widgets\MainMenu::widget();?>
