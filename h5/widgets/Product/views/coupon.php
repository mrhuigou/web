<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/9/5
 * Time: 10:17
 */
?>
<?php if($model){?>
<!--优惠券-->
<div class="whitebg bdt bdb pt5 pb5 m5">
	<div class="flex-col f12">
		<div class="flex-item-2 tc gray9">
			领券
		</div>
		<div class="flex-item-10 clearfix">
	<?php foreach($model as $coupon){?>
			<a class="coupon_unit red coupon-item-apply " href="javascript:;" data-content="<?=$coupon->code?>">
				<div class="expe_disc">
					<div class="expeNum">
						<?php if ($coupon->model!=='BUY_GIFTS') { ?>
							<?php if($coupon->type == 'F'){ ?>
							<span class="rmb">￥</span> <span class="actual-number"><?=$coupon->getRealDiscount()?></span>
							<?php }else{ ?>
								<?=$coupon->getRealDiscount()?> 折
							<?php } ?>
						<?php } else { ?>
							<?= $coupon->name ?>
						<?php } ?>
					</div>
<!--					<div class="condi_msg ellipsis" style="max-width: 200px;">--><?//=$coupon->comment?$coupon->comment:$coupon->description?><!--</div>-->
					<div class="condi_msg ellipsis" style="max-width: 200px;"><?=$coupon->name?$coupon->name:$coupon->description?></div>
				</div>
				<div class="coupon_icon"></div>
				<div class="oper_msg">
					<div class="up" >领</div>
					<div class="down">取</div>
				</div>
				<em class="left_m"></em>
			</a>
	<?php } ?>
		</div>
	</div>
</div>
<?php } ?>