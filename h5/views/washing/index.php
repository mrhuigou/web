<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/20
 * Time: 15:13
 */
$this->title = "洗化专区";
?>
<header class="header w">
	<a class="pa-lt iconfont leftarr" href="javascript:history.back();"></a>
	<div class="pr pl30 pr5 ">
		<form action="<?php echo \yii\helpers\Url::to(['/search/index'])?>" method="get" id="search_form">
			<input class="input-text minput w " type="text" name="keyword"
			       value="<?= \yii\helpers\Html::encode(Yii::$app->request->get('keyword')) ?>" autocomplete="off"
			       tabindex="1">
			<a href="javascript:void(0)" class="search-btn iconfont">&#xe601;</a>
		</form>
	</div>
</header>
<div class="content  bc">
    <section class="veiwport" style="max-width: 640px;">
		<!-- 活动焦点图 -->
		<?php if ($swiper) { ?>
			<div class="swiper-container" id="swiper-container_banner">
				<div class="swiper-wrapper">
					<?php foreach ($swiper as $value) { ?>
						<div class="swiper-slide">
							<a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
								<img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class=" w ">
							</a>
						</div>
					<?php } ?>
				</div>
				<!-- Add Pagination -->
				<div class="swiper-pagination swiper-pagination-white swiper-pagination-banner"></div>
			</div>
		<?php } ?>
		<!-- 优选 -->
		<?php if ($ad_1) { ?>
			<div class="row mb5">
				<?php foreach ($ad_1 as $value) { ?>
					<div class="col-2 bdr bdb">
						<a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
							<img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
						</a>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if ($ad_2) { ?>
				<?php foreach ($ad_2 as $value) { ?>
					<div class="w mb5">
						<a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
							<img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
						</a>
					</div>
				<?php } ?>
		<?php } ?>


        <div class="flex-col bg-wh mb5">
	        <?php if ($floor_1_01) { ?>
	        <?php foreach ($floor_1_01 as $value) { ?>
            <a href="<?= \yii\helpers\Url::to($value->link_url) ?>" class="flex-item-4" style="height: 16rem;">
                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
            </a>
		        <?php } ?>
	        <?php } ?>
	        <?php if ($floor_1_02) { ?>
	        <?php foreach ($floor_1_02 as $value) { ?>
            <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="flex-item-4 pr" style="height: 16rem;">
                <img src="<?= \common\component\image\Image::resize($value->product->image,190,190) ?>" class="w">
	            <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                <div class="p5 tc" style="font-size:1.2rem;">
                    <p class="row-two"><?=$value->product->description->name?></p>
                    <span class="red">￥<?=$value->product->getPrice()?></span>
                </div>
            </a>
		        <?php } ?>
	        <?php } ?>
        </div>

        <div class="flex-col bg-wh mb5">
			<?php if ($floor_2_01) { ?>
				<?php foreach ($floor_2_01 as $value) { ?>
                    <a href="<?= \yii\helpers\Url::to($value->link_url) ?>" class="flex-item-4" style="height: 16rem;">
                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                    </a>
				<?php } ?>
			<?php } ?>
			<?php if ($floor_2_02) { ?>
				<?php foreach ($floor_2_02 as $value) { ?>
                    <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="flex-item-4 pr" style="height: 16rem;">
                        <img src="<?= \common\component\image\Image::resize($value->product->image,190,190) ?>" class="w">
	                    <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                        <div class="p5 tc" style="font-size:1.2rem;">
                            <p class="row-two"><?=$value->product->description->name?></p>
                            <span class="red">￥<?=$value->product->getPrice()?></span>
                        </div>
                    </a>
				<?php } ?>
			<?php } ?>
        </div>

        <div class="flex-col bg-wh mb5">
			<?php if ($floor_3_01) { ?>
				<?php foreach ($floor_3_01 as $value) { ?>
                    <a href="<?= \yii\helpers\Url::to($value->link_url) ?>" class="flex-item-4" style="height: 16rem;">
                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                    </a>
				<?php } ?>
			<?php } ?>
			<?php if ($floor_3_02) { ?>
				<?php foreach ($floor_3_02 as $value) { ?>
                    <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="flex-item-4 pr" style="height: 16rem;">
                        <img src="<?= \common\component\image\Image::resize($value->product->image,190,190) ?>" class="w">
	                    <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                        <div class="p5 tc" style="font-size:1.2rem;">
                            <p class="row-two"><?=$value->product->description->name?></p>
                            <span class="red">￥<?=$value->product->getPrice()?></span>
                        </div>
                    </a>
				<?php } ?>
			<?php } ?>
        </div>

        <div class="flex-col bg-wh mb5">
			<?php if ($floor_4_01) { ?>
				<?php foreach ($floor_4_01 as $value) { ?>
                    <a href="<?= \yii\helpers\Url::to($value->link_url) ?>" class="flex-item-4" style="height: 16rem;">
                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                    </a>
				<?php } ?>
			<?php } ?>
			<?php if ($floor_4_02) { ?>
				<?php foreach ($floor_4_02 as $value) { ?>
                    <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="flex-item-4 pr" style="height: 16rem;">
                        <img src="<?= \common\component\image\Image::resize($value->product->image,190,190) ?>" class="w">
	                    <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                        <div class="p5 tc" style="font-size:1.2rem;">
                            <p class="row-two"><?=$value->product->description->name?></p>
                            <span class="red">￥<?=$value->product->getPrice()?></span>
                        </div>
                    </a>
				<?php } ?>
			<?php } ?>
        </div>

        <div class="flex-col bg-wh mb5">
			<?php if ($floor_5_01) { ?>
				<?php foreach ($floor_5_01 as $value) { ?>
                    <a href="<?= \yii\helpers\Url::to($value->link_url) ?>" class="flex-item-4" style="height: 16rem;">
                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                    </a>
				<?php } ?>
			<?php } ?>
			<?php if ($floor_5_02) { ?>
				<?php foreach ($floor_5_02 as $value) { ?>
                    <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="flex-item-4 pr" style="height: 16rem;">
                        <img src="<?= \common\component\image\Image::resize($value->product->image,190,190) ?>" class="w">
	                    <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                        <div class="p5 tc" style="font-size:1.2rem;">
                            <p class="row-two"><?=$value->product->description->name?></p>
                            <span class="red">￥<?=$value->product->getPrice()?></span>
                        </div>
                    </a>
				<?php } ?>
			<?php } ?>
        </div>

        <div class="flex-col bg-wh mb5">
			<?php if ($floor_6_01) { ?>
				<?php foreach ($floor_6_01 as $value) { ?>
                    <a href="<?= \yii\helpers\Url::to($value->link_url) ?>" class="flex-item-4" style="height: 16rem;">
                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                    </a>
				<?php } ?>
			<?php } ?>
			<?php if ($floor_6_02) { ?>
				<?php foreach ($floor_6_02 as $value) { ?>
                    <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="flex-item-4 pr" style="height: 16rem;">
                        <img src="<?= \common\component\image\Image::resize($value->product->image,190,190) ?>" class="w">
	                    <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                        <div class="p5 tc" style="font-size:1.2rem;">
                            <p class="row-two"><?=$value->product->description->name?></p>
                            <span class="red">￥<?=$value->product->getPrice()?></span>
                        </div>
                    </a>
				<?php } ?>
			<?php } ?>
        </div>

	    <?php if($brand){?>
            <div class="row nav2 mb5 mt5">
			    <?php foreach ($brand as $value){?>
                    <div class="item col-5 p5" >
                        <a href="<?= \yii\helpers\Url::to($value->link_url) ?>" >
                            <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="img-circle bd" style="height:50px;width: 50px;">
                        </a>
                    </div>
			    <?php }?>
            </div>
	    <?php } ?>
	    <?=\h5\widgets\Block\Hongbao::widget(['code'=>'washing'])?>
	</section>
</div>
<?= h5\widgets\MainMenu::widget(); ?>
<?php $this->beginBlock('JS_END') ?>
$.backtop(".content");
var swiper_banner = new Swiper('#swiper-container_banner', {
pagination: '.swiper-pagination-banner',
paginationClickable: true,
spaceBetween: 0,
centeredSlides: true,
autoplay: 4000,
autoplayDisableOnInteraction: false
});
Ad_Sys_Code();
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>
