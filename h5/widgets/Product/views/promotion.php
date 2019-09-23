<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/16
 * Time: 14:13
 */
?>
<?php if ($model) { ?>
	<!--促销-->
	<div class="whitebg bdt bdb pt5 pb5 mb5">
		<div class="flex-col f12">
			<div class="flex-item-2 tc gray9 ">
				促销
			</div>
			<div class="flex-item-10">
				<?php foreach ($model as $promotion) { ?>
				<p class="mb5 f12">
					<?php if ($promotion->stop_buy_type != 'NONE') { ?>
						<span class="bd-red red br2">&nbsp;限购&nbsp;</span>
						<?php if ($promotion->stop_buy_type == 'ORDER') { ?>
							每单限购<em
								class="red bold"><?= $promotion->stop_buy_quantity ?></em>件
						<?php } elseif ($promotion->stop_buy_type == 'DAY') { ?>
							每日限购<em	class="red bold"><?= $promotion->stop_buy_quantity ?></em>件
						<?php } elseif ($promotion->stop_buy_type == 'PROMOTION_TIME') { ?>
							促销期间限购<em	class="red bold"><?= $promotion->stop_buy_quantity ?></em>件
						<?php } ?>
					<?php } ?>
					</p>
					<p class="lh150 f12 mb5">
					<em class="bd-red red pl5 pr5 "><?= $promotion->product->getSku() ?></em>
					<?php if ($promotion->end_quantity) { ?>
						<?= $promotion->begin_quantity ?>-<?= $promotion->end_quantity ?>件
					<?php } ?>
					<?php if ($promotion->begin_amount > 0) { ?>
						满￥<?= $promotion->begin_amount ?>元
					<?php } ?>
					促销价：
					<?php if ($promotion->pricetype == 'UNITPRICE') { ?>
						<em class="red fb">¥<?= number_format($promotion->price, 2) ?></em>
					<?php } else { ?>
						<em class="red fb">¥<?= number_format($promotion->rebate * $promotion->product->vip_price, 2) ?></em>
					<?php } ?>
				</p>
					<?php if ($promotion->gifts) {
						foreach ($promotion->gifts as $gift) {
							?>
							<p>
								赠：<?= $gift->product->description->name ?>【<?= $gift->product->getSku() ?>】
								赠送比例：买<?= $gift->base_number ?>送<?= $gift->quantity ?>赠品
							</p>
						<?php }
					} ?>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } ?>
