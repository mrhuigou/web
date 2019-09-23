<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/9
 * Time: 13:55
 */
?>
<header class="fx-top bs-bottom whitebg lh44">
	<div class="flex-col tc">
		<a class="flex-item-2" href="javascript:history.back();">
			<i class="aui-icon aui-icon-left green f28"></i>
		</a>
		<div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($title) ?>
		</div>
		<a class="flex-item-2" href="<?=\yii\helpers\Url::to(['/user/index'])?>">
			<i class="iconfont green f28">&#xe603;</i>
		</a>
	</div>
</header>
<div class="w mt50"></div>