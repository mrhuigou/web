<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/10
 * Time: 10:31
 */
$this->title="领券中心";
?>
<div class="w1100 bc">
	<!--领券banner-->
	<img width="1100" height="285" class="db" alt="领券banner" src="/assets/images/coupon.jpg">
	<?php if(isset($model['ORDER']) && $model['ORDER']){ ?>
		<h2 class="f18 mt30 mb15">店铺优惠券</h2>
		<div class="clearfix">
			<?php foreach($model['ORDER'] as $coupon){?>
				<div class="pw33 fl">
					<div class="p5">
						<div class="bd whitebg clearfix pr">
							<div class="pw35 fl">
								<img class="db w" src="<?=\common\component\image\Image::resize($coupon->image_url,300,300)?>">
							</div>
							<div class="pw45 fl">
								<div class="p5 lh">
									<p class="f25 red"><?=$coupon->type=='F'?"￥".$coupon->discount:$coupon->getRealDiscount()."<i class='f12'>折</i>"?></p>
									<div class="lh200">
										<p  class="f12  gray9"> <?=$coupon->description?></p>
									</div>
								</div>
							</div>
							<?php if($coupon->quantity>count($coupon->users)){?>
							<div class="pw20 fr bluebg tc pa-rt b0 pt40">
								<p class="white pb5">剩余<?=number_format((($coupon->quantity-count($coupon->users))/$coupon->quantity)*100,0)?>%</p>
								<?php if($coupon->getIsHade(Yii::$app->user->getId())){?>
									<a class="f14 gray" href="javascript:;">已领取</a>
								<?php }else{ ?>
									<a class="red f14 applay_coupon" href="javascript:;" data-content="<?=$coupon->coupon_id?>">立即领用</a>
								<?php } ?>
							</div>
							<?php }else{?>
							<div class="pw20 fr graybg2 tc pa-rt b0 pt35">
								<i class="iconfont f60 lh">&#xe634;</i>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if(isset($model['BUY_GIFTS']) && $model['BUY_GIFTS']){ ?>
		<h2 class="f18 mt30 mb15">买赠优惠券</h2>
		<div class="clearfix">
			<?php foreach($model['BUY_GIFTS'] as $coupon){?>
				<div class="pw33 fl">
					<div class="p5">
						<div class="bd whitebg clearfix pr">
							<div class="pw35 fl">
								<img class="db w" src="<?=\common\component\image\Image::resize($coupon->image_url,300,300)?>">
							</div>
							<div class="pw45 fl">
								<div class="p5 lh">
									<p class="f25 red"><?=$coupon->name?></p>
									<div class="lh200">
										<p  class="f12  gray9"><?=$coupon->comment?$coupon->comment:$coupon->description?></p>
									</div>
								</div>
							</div>
							<?php if($coupon->quantity>count($coupon->users)){?>
								<div class="pw20 fr bluebg tc pa-rt b0 pt40">
									<p class="white pb5">剩余<?=number_format((($coupon->quantity-count($coupon->users))/$coupon->quantity)*100,0)?>%</p>
									<?php if($coupon->getIsHade(Yii::$app->user->getId())){?>
										<a class="f14 gray" href="javascript:;">已领取</a>
									<?php }else{ ?>
										<a class="red f14 applay_coupon" href="javascript:;" data-content="<?=$coupon->coupon_id?>">立即领用</a>
									<?php } ?>
								</div>
							<?php }else{?>
								<div class="pw20 fr graybg2 tc pa-rt b0 pt35">
									<i class="iconfont f60 lh">&#xe634;</i>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if(isset($model['BRAND']) && $model['BRAND']){ ?>
		<h2 class="f18 mt30 mb15">品牌优惠券</h2>
		<div class="clearfix">
			<?php foreach($model['BRAND'] as $coupon){?>
				<div class="pw33 fl">
					<div class="p5">
						<div class="bd whitebg clearfix pr">
							<div class="pw35 fl">
								<img class="db w" src="<?=\common\component\image\Image::resize($coupon->image_url,300,300)?>">
							</div>
							<div class="pw45 fl">
								<div class="p5 lh">
									<p class="f25 red"><?=$coupon->type=='F'?"￥".$coupon->discount:$coupon->getRealDiscount()."<i class='f12'>折</i>"?></p>
									<div class="lh200">
										<p  class="f12  gray9"> <?=$coupon->description?></p>

									</div>
								</div>
							</div>
							<?php if($coupon->quantity>count($coupon->users)){?>
								<div class="pw20 fr bluebg tc pa-rt b0 pt40">
									<p class="white pb5">剩余<?=number_format((($coupon->quantity-count($coupon->users))/$coupon->quantity)*100,0)?>%</p>
									<?php if($coupon->getIsHade(Yii::$app->user->getId())){?>
										<a class="f14 gray" href="javascript:;">已领取</a>
									<?php }else{ ?>
										<a class="red f14 applay_coupon" href="javascript:;" data-content="<?=$coupon->coupon_id?>">立即领用</a>
									<?php } ?>
								</div>
							<?php }else{?>
								<div class="pw20 fr graybg2 tc pa-rt b0 pt35">
									<i class="iconfont f60 lh">&#xe634;</i>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if(isset($model['CLASSIFY']) && $model['CLASSIFY']){ ?>
		<h2 class="f18 mt30 mb15">分类优惠券</h2>
		<div class="clearfix">
			<?php foreach($model['CLASSIFY'] as $coupon){?>
				<div class="pw33 fl">
					<div class="p5">
						<div class="bd whitebg clearfix pr">
							<div class="pw35 fl">
								<img class="db w" src="<?=\common\component\image\Image::resize($coupon->image_url,300,300)?>">
							</div>
							<div class="pw45 fl">
								<div class="p5 lh">
									<p class="f25 red"><?=$coupon->type=='F'?"￥".$coupon->discount:$coupon->getRealDiscount()."<i class='f12'>折</i>"?></p>
									<div class="lh200">
										<p  class="f12  gray9"> <?=$coupon->description?></p>
									</div>
								</div>
							</div>
							<?php if($coupon->quantity>count($coupon->users)){?>
								<div class="pw20 fr bluebg tc pa-rt b0 pt40">
									<p class="white pb5">剩余<?=number_format((($coupon->quantity-count($coupon->users))/$coupon->quantity)*100,0)?>%</p>
									<?php if($coupon->getIsHade(Yii::$app->user->getId())){?>
										<a class="f14 gray" href="javascript:;">已领取</a>
									<?php }else{ ?>
										<a class="red f14 applay_coupon" href="javascript:;" data-content="<?=$coupon->coupon_id?>">立即领用</a>
									<?php } ?>
								</div>
							<?php }else{?>
								<div class="pw20 fr graybg2 tc pa-rt b0 pt35">
									<i class="iconfont f60 lh">&#xe634;</i>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if(isset($model['PRODUCT']) && $model['PRODUCT']){ ?>
		<h2 class="f18 mt30 mb15">商品券</h2>
		<div class="clearfix">
			<?php foreach($model['PRODUCT'] as $coupon){?>
				<div class="pw33 fl">
					<div class="p5">
						<div class="bd whitebg clearfix pr">
							<div class="pw35 fl">
								<img class="db w" src="<?=\common\component\image\Image::resize($coupon->image_url,300,300)?>">
							</div>
							<div class="pw45 fl">
								<div class="p5 lh">
									<p class="f25 red"><?=$coupon->type=='F'?"￥".$coupon->discount:$coupon->getRealDiscount()."<i class='f12'>折</i>"?></p>
									<div class="lh200 ">
										<p  class="f12  gray9"> <?=$coupon->description?></p>
									</div>
								</div>
							</div>
							<?php if($coupon->quantity>count($coupon->users)){?>
								<div class="pw20 fr bluebg tc pa-rt b0 pt40">
									<p class="white pb5">剩余<?=number_format((($coupon->quantity-count($coupon->users))/$coupon->quantity)*100,0)?>%</p>
									<?php if($coupon->getIsHade(Yii::$app->user->getId())){?>
									<a class="f14 gray" href="javascript:;">已领取</a>
									<?php }else{ ?>
									<a class="red f14 applay_coupon" href="javascript:;" data-content="<?=$coupon->coupon_id?>">立即领用</a>
									<?php } ?>
								</div>
							<?php }else{?>
								<div class="pw20 fr graybg2 tc pa-rt b0 pt35">
									<i class="iconfont f60 lh">&#xe634;</i>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
<?php $this->beginBlock('JS_END') ?>
$(".applay_coupon").click(function(){
$.post('/coupon/apply',{'coupon_id':$(this).attr('data-content')},function(data){
if(data.status){
	if(data.message){
	alert(data.message);
	}
	if(data.redirect){
	location.href=data.redirect;
	}else{
	location.reload();
	}
}else{
	if(data.message){
	alert(data.message);
	}
	if(data.redirect){
	location.href=data.redirect;
	}
	}
},'json');
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
