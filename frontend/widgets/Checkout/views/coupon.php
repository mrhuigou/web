<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/27
 * Time: 16:54
 */
?>
<div class="w900 fl bdr">
	<input class="input linput pw97" placeholder="可告诉我们您的特殊要求，最多可输入200字。" name="CheckoutForm[comment][<?= $store_id ?>]"
	       value="">
	<?php if ($model) { ?>
		<div class="clearfix mt10 store-coupon" data-content-id="<?= $store_id ?>">
			<input type="hidden" name="CheckoutForm[coupon][<?= $store_id ?>]" class="store_coupon_data"
			       value="<?= $model['cur'] ?>">
			<?php foreach ($model['data'] as $value) { ?>
				<div
					class="order-coupon checkbox_coupon <?= in_array($value->customer_coupon_id, explode(',', $model['cur'])) ? 'cur' : '' ?>"
					data-content-id="<?= $value->customer_coupon_id ?>">
					<span class="btn red order-coupon-concel">&nbsp;已选择&nbsp;</span>

					<p class="w red ">
						<?php if($value->coupon->model!=='BUY_GIFTS'){?>
						<?php if ($value->coupon->type == 'F') { ?>
							<i class="f12">￥</i>
							<em class="f30"><?=$value->coupon->getRealDiscount()?></em>
						<?php } else { ?>
							<em class="f30"><?=$value->coupon->getRealDiscount()?></em>
							<i class="f12">折</i>
						<?php } ?>
						<?php }else{ ?>
							<?=$value->coupon->name?>
						<?php }?>
					</p>
					<div class="w">
						<?= $value->coupon->getDescription() ?>
						<p><?= date('m-d', strtotime($value->start_time)) ?>
							~<?= date('m-d', strtotime($value->end_time)) ?></p>
					</div>
					<i class="iconfont">&#xe627;</i>
				</div>
			<?php } ?>
			<?php foreach ($model['order'] as $value) { ?>
				<div
					class="order-coupon radio_coupon yellowbg <?= in_array($value->customer_coupon_id, explode(',', $model['cur'])) ? 'cur' : '' ?> "
					data-content-id="<?= $value->customer_coupon_id ?>">
					<span class="btn red order-coupon-concel">&nbsp;已选择&nbsp;</span>
					<p class="w red ">
						<?php if ($value->coupon->type == 'F') { ?>
							<i class="f12">￥</i>
							<em class="f30"><?=$value->coupon->getRealDiscount()?></em>
						<?php } else { ?>
							<em class="f30"><?=$value->coupon->getRealDiscount()?></em>
							<i class="f12">折</i>
						<?php } ?>
					</p>
					<div class="w">
						<?= $value->coupon->getDescription() ?>
						<p><?= date('m-d', strtotime($value->start_time)) ?>
							~<?= date('m-d', strtotime($value->end_time)) ?></p>
					</div>
					<i class="iconfont">&#xe627;</i>
				</div>
			<?php } ?>
		</div>
		<div class="order-coupon-show" style="display: none;">
			更多优惠券
			<i class="iconfont">&#xe60b;</i>
		</div>
	<?php } ?>
</div>
<?php $this->beginBlock('JS_END') ?>
$(".order-coupon-show").click(function(){
if($(this).parents(".order-extra").find(".order-coupon:gt(3)").is(":visible")){
$(this).parents(".order-extra").find(".order-coupon:gt(3)").hide();
$(this).find("i").html("&#xe60b;");
} else {
$(this).parents(".order-extra").find(".order-coupon:gt(3)").show();
$(this).find("i").html("&#xe60a;");
}
});
$(".checkbox_coupon").bind('click',function(){
if($(this).hasClass('cur')){
$(this).removeClass("cur");
}else{
$(this).addClass("cur");
}
var store_coupon_data=[];
var coupons=$(this).parents(".store-coupon").find(".order-coupon.cur");
coupons.each(function(){
store_coupon_data.push($(this).attr("data-content-id"));
});
$(this).parents(".store-coupon").find(".store_coupon_data").val(store_coupon_data.join(','));
var store_id=$(this).parents(".store-coupon").attr("data-content-id");
AsyUpdateShopTotals(store_id);
});

$(".radio_coupon").bind('click',function(){
if($(this).hasClass('cur')){
$(this).removeClass("cur");
}else{
$(this).addClass("cur").siblings().not(".checkbox_coupon").removeClass("cur");
}

var store_coupon_data=[];
var coupons=$(this).parents(".store-coupon").find(".order-coupon.cur");
coupons.each(function(){
store_coupon_data.push($(this).attr("data-content-id"));
});
$(this).parents(".store-coupon").find(".store_coupon_data").val(store_coupon_data.join(','));
var store_id=$(this).parents(".store-coupon").attr("data-content-id");
AsyUpdateShopTotals(store_id);
});
$(".store-coupon").each(function(){
var store_id=$(this).attr("data-content-id");
$(this).find(".checkbox_coupon").each(function(){
$(this).trigger('click');
});
var self =$(this).find(".radio_coupon").first();
if(self){
self.trigger('click');
}
var store_coupon_data=[];
var coupons=$(this).find(".order-coupon.cur");
coupons.each(function(){
store_coupon_data.push($(this).attr("data-content-id"));
});
$(this).find(".store_coupon_data").val(store_coupon_data.join(','));
AsyUpdateShopTotals(store_id);
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>

