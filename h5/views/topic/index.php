<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title =$model?$model->name:"热门活动";
?>
<section class="veiwport pb50" style="max-width: inherit;">
			<ul class="filter redFilter two f16 clearfix none" style="border-bottom: 1px solid #ff463c;">
				<li class="cur">
					<a href="#">热门活动</a>
				</li>
				<li>
					<a href="#">活动预热</a>
				</li>
			</ul>
<?php if ($details) { ?>
	<?php foreach ($details as $promotion) { ?>
			<div class="w mt10" >
			<a href="<?=Url::to(['/topic/detail','code'=>$promotion->code])?>" class="db">
			<img data-original="<?= \common\component\image\Image::resize($promotion->image_url, 640, 230,9) ?>" class="w lazy">
			</a>
				<div class="brand-info none">
					<a href="<?=Url::to(['/topic/detail','code'=>$promotion->code])?>" class="db">
						<h2 class="f16 fb  tc "><?= $promotion->name ?></h2>
					</a>
					<div class="bdt tc">
						<h2 class="f16 fb red pt10 pb5"><?= $promotion->description ?></h2>
						<p><?= $promotion->date_end ?></p>
					</div>
				</div>
			</div>
			<!--品牌活动列表-->
			<?php if ($details = $promotion->getTopDetails(4)) {?>
			<div class="swiper-container brandList whitebg">
				<div class="swiper-wrapper">
				<?php foreach ($details as $detail) {
					if(!$detail->product){
						continue;
					}
				?>
					<div class="swiper-slide">
						<a href="<?=Url::to(['/product/index','shop_code'=>$detail->product->store_code,'product_code'=>$detail->product->product_code])?>" class="db">
							<img data-original="<?= \common\component\image\Image::resize($detail->product->image, 156, 156) ?>"  class="w lazy">
						</a>
						<div class="pt5">
							<a href="<?=Url::to(['/product/index','shop_code'=>$detail->product->store_code,'product_code'=>$detail->product->product_code])?>" class="f12 db pb10 mxh35">
							<?=$detail->product->description->name?>
							 </a>
							<p class="mt5 mb5 tc">
							<span class="red vm">￥<?= $detail->getCurPrice() ?></span>
							</p>
							<p class="mt5 mb5 tc">
							<a href="javascript:;"  onclick="AddCart(<?=$detail->product->product_base_id?>)" >
							<i class="iconfont p5 " style="width: 30px;height: 30px;">&#xe688;</i>
							</a>
							</p>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			<?php } ?>
			<?php } ?>
		</section>
	</section>
<?php $this->beginBlock("JS") ?>
//爆款滑动
var brandList = new Swiper('.brandList', {
slidesPerView: "auto"
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS'], \yii\web\View::POS_READY);
?>
<?= h5\widgets\MainMenu::widget(); ?>