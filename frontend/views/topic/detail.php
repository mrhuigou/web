<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use \common\component\image\Image;

/* @var $this yii\web\View */
$this->title = $model->name;
?>
<div id="content">
<div class="w1100 bc">
	<div class="whitebg mb10 clearfix">
		<div class="fl  pw70">
			<?php if ($model->image_url) { ?>
				<img src="<?= \common\component\image\Image::resize($model->image_url, 1200, 285,9) ?>" alt="" class="db w ">
			<?php } ?>
		</div>
		<div class="fr  pw30 tc">
			<div class="p lh200 pt80 p10">
				<p class="f14 fb"><?= $model->name ?></p>
				<p class="f14 gray6"><?= $model->description ?></p>
			</div>
			<div class="clearfix ">
			<p class="tc">距离活动结束还有</p>
			<p class="time-item p10 tc ">
				<span id="day_show" style="float: none">0天</span>
				<strong id="hour_show">00时</strong>
				<strong id="minute_show"><s></s>00分</strong>
				<strong id="second_show"><s></s>00秒</strong>
			</p>
			</div>
		</div>
	</div>
	<!--列表-->
	<?php if ($details) { ?>
		<div class="clearfix lr-m5">
			<?php foreach($details as $detail){?>
				<div class="pw20 fl">
					<div class="p5">
						<div class="bd whitebg">
							<a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$detail->product->store_code,'product_code'=>$detail->product->product_code])?>" class="db">
								<img src="<?= \common\component\image\Image::resize($detail->product->image, 500, 500) ?>" alt="<?= $detail->product->description->name ?>" class="w db">
							</a>
							<div class="p10">
								<span class="red f16">￥<?= $detail->getCurPrice() ?></span>
								<a href="#" class="mxh40 db">
									<?= $detail->product->description->name ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
</div>
<?php $this->beginBlock('JS_END') ?>
	timer(<?=intval(strtotime($model->date_end)-time())?>);
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);?>