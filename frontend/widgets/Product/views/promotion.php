<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/16
 * Time: 14:13
 */
?>
<?php if($model) { ?>
	<dl class="tm-price-panel">
		<dt class="tb-metatit" style="width:60px;">促销信息：</dt>
		<dd>
				<?php foreach($model as $promotion){ ?>
						<p class=" red" >
							《<?=$promotion->promotion->name?>》
							购买
							<em style="color: #38b">【<?=$promotion->product->getSku()?>】</em>
							<?php if($promotion->end_quantity){ ?>
							<?=$promotion->begin_quantity?>-<?=$promotion->end_quantity?>件
							<?php } ?>
							<?php if($promotion->begin_amount>0){ ?>
								满￥<?=$promotion->begin_amount?>元
							<?php } ?>
							享受促销价：
							<?php if($promotion->pricetype=='UNITPRICE'){ ?>
								<em class="red fb">¥<?=number_format($promotion->price,2)?></em>元
							<?php }else{ ?>
								<em class="red fb">¥<?=number_format($promotion->rebate*$promotion->product->vip_price,2)?></em>元
							<?php }?>
							<?php if($promotion->stop_buy_type != 'NONE'){ ?>
								<?php if($promotion->stop_buy_type == 'ORDER'){ ?>
									单张订单限购<em class="red bold"><?=$promotion->stop_buy_quantity?></em>件
								<?php }elseif($promotion->stop_buy_type == 'DAY'){?>
									每日限购：<em class="red bold"><?=$promotion->stop_buy_quantity?></em>件
								<?php }elseif($promotion->stop_buy_type == 'PROMOTION_TIME'){?>
									促销期间限购<em class="red bold"><?=$promotion->stop_buy_quantity?></em>件
								<?php }?>
							<?php }?>
						</p>
						<?php if($promotion->gifts){
							foreach($promotion->gifts as $gift){
								?>
								<p>
									赠：<?=$gift->product->description->name?>【<?=$gift->product->getSku()?>】
									赠送比例：买<?=$gift->base_number?>送<?=$gift->quantity?>
								</p>
							<?php }  }?>
				<?php } ?>

		</dd>
	</dl>
<?php } ?>
