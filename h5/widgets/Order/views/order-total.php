<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/30
 * Time: 10:17
 */
?>
<?php if ($model) { ?>
	<h1 class="whitebg fb lh37 bdt bdb pl5 ">订单小计</h1>
	<div class=" bdb  mb10 whitebg">
		<?php foreach ($model as $order_totals) { ?>
			<p class="clearfix tr p5 ">
				<span class=" fb "><?= $order_totals->title ?></span>
				<span class=" red mr5"><?= $order_totals->text ?></span>
			</p>
		<?php } ?>
	</div>
<?php } ?>
