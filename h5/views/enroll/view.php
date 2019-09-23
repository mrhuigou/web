<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/28
 * Time: 15:58
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '报名结果';
?>
<header class="fx-top opc-f lh44 bluebg white">
	<div class="flex-col tc">
		<a class="flex-item-2" href="/enroll/index">
			<i class="aui-icon aui-icon-left  f28"></i>
		</a>
		<div class="flex-item-8 f16">
			<?= Html::encode($this->title) ?>
		</div>
		<a class="flex-item-2 share-guide " href="javascript:;">
			<i class="aui-icon aui-icon-share  f28"></i>
		</a>
	</div>
</header>
<section class="veiwport pl5 pr5 pt50  ">
	<div class="m10">
		<figure class="info-tips  p10">
			<i class="iconfont  green f50">&#xe61a;</i>
			<figcaption class="m10 lh200">报名成功<br/>客服人员会及时与您确认审核</figcaption>
		</figure>
		<h1 class=" lh200  red  tc ">长按识别二维码</h1>
		<p class="tc">
			<img src="/assets/images/wx.jpg" alt="二维码" width="150" height="150">
		</p>
		<p class="gray9 mt10 tc">关注家润公众号，了解更多资讯</p>
	</div>
</section>