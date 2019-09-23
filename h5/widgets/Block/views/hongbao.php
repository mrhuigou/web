<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/27
 * Time: 17:07
 */
?>
<!-- 领红包 -->
<div class="getRedbag" style="bottom: 150px;right: 50px;">
	<a class="db " href="<?=\yii\helpers\Url::to(['/hongbao/block','code'=>$code],true)?>">
	<svg class="icon shakesmall" aria-hidden="true">
		<use xlink:href="#icon-yuanbaodai-01"></use>
	</svg>
	<p class="red">领红包</p>
	</a>
</div>
