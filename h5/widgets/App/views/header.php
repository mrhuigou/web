<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/12/7
 * Time: 19:04
 */
use yii\helpers\Html;
?>
<header class="fx-top bs-bottom whitebg lh44">
	<div class="flex-col tc">
		<a class="flex-item-2" href="javascript:history.back();">
			<i class="aui-icon aui-icon-left green f28"></i>
		</a>
		<div class="flex-item-8 f16">
			<?=Html::encode($title)?>
		</div>
		<a class="flex-item-2" href="javascript:;" id="share-btn">
			<i class="iconfont green f28">&#xe644;</i>
		</a>
	</div>
</header>
<div class="w mt50"></div>
