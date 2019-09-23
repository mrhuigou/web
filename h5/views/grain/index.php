<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/24
 * Time: 16:50
 */
$this->title="粮油专区";
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
<div class="content  bc" >
	<section class="veiwport" >
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
		<?php if ($ad_5) { ?>
            <!-- 广告位 -->
            <div class="flex-col  mb10">
				<?php foreach ($ad_5 as $value) { ?>
                    <div class="flex-item-12">
                        <a href="<?= \yii\helpers\Url::to($value->link_url) ?>"><img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w"></a>
                    </div>
				<?php } ?>
            </div>
		<?php } ?>
		<?php if($nav){ ?>
			<div class="row nav2 mb5">
				<?php foreach ($nav as $value){?>
					<a href="<?= \yii\helpers\Url::to($value->link_url) ?>" class="item col-4 db " >
						<img src="<?= \common\component\image\Image::resize($value->source_url,100,100) ?>" class=" img-circle bd" style="width: 60px;height: 60px;">
						<span><?= $value->title ?></span>
					</a>
				<?php } ?>
			</div>
		<?php } ?>
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
        <?php
        if($ad_1 && $ad_2){
            $group_count=min(count($ad_1),count($ad_2));
            ?>
        <div class="swiper-container mt5 mb10" id="swiper-container_news">
            <div class="swiper-wrapper">
				<?php for ($i=0;$i<$group_count;$i++) { ?>
                    <div class="swiper-slide ">

                        <div class="fl">
                            <a href="<?= \yii\helpers\Url::to($ad_1[$i]->link_url) ?>"><img src="<?= \common\component\image\Image::resize($ad_1[$i]->source_url) ?>" style="width: 23rem;height: 11rem;" ></a>
                        </div>
                        <div class="fr">
                            <a href="<?= \yii\helpers\Url::to($ad_2[$i]->link_url) ?>"><img src="<?= \common\component\image\Image::resize($ad_2[$i]->source_url) ?>" style="width: 9rem;height: 11rem;"></a>
                        </div>

                    </div>
				<?php } ?>
            </div>
            <div class="swiper-pagination swiper-pagination-white swiper-pagination-news"></div>
        </div>
        <?php }?>



        <?php if ($ad_3) { ?>
			<!-- 广告位 -->
			<div class="flex-col mt5 mb10">
				<?php foreach ($ad_3 as $value) { ?>
					<div class="flex-item-12">
						<a href="<?= \yii\helpers\Url::to($value->link_url) ?>"><img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w"></a>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if ($ad_4) { ?>
			<!-- 广告位 -->
			<div class="flex-col mt5 mb10">
				<?php foreach ($ad_4 as $value) { ?>
					<div class="flex-item-6">
						<a href="<?= \yii\helpers\Url::to($value->link_url) ?>"><img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w"></a>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

		<div class="bg-wh w bs-b" id="nav-fix">
			<div class="normal-items y normal-items2 row-more" id="nav-menu">
				<span class="cur item"><a  href="#item_1">爆品推荐</a></span>
				<span class="item"><a  href="#item_2">米面</a></span>
				<span class="item"><a  href="#item_3">食用油</a></span>
				<span class="item"><a  href="#item_4">方便食品</a></span>
				<span class="item"><a  href="#item_5">挂面</a></span>
				<span class="item"><a  href="#item_6">调味</a></span>
				<span class="item"><a  href="#item_7">南北干货</a></span>
				<span class="item"><a  href="#item_8">杂粮</a></span>
				<span class="item"><a  href="#item_9">酱菜</a></span>
				<i class="more iconfont" style="display: none;">&#xe60a;</i>
			</div>
		</div>
		<div id="item_1">
			<?php if ($floor_1_01) { ?>
				<div class="tit-- green">
					<span class="vm">爆品推荐</span>
				</div>
				<div class="row tc bg-wh">
					<?php foreach ($floor_1_01 as $value) { ?>
						<div class="col-2 p5 bdr bdb">
							<a href="<?= \yii\helpers\Url::to(['/product/index', 'shop_code' => $value->product->store_code, 'product_code' => $value->product->product_code]) ?>"
							   class="db w">
								<div class="item">
									<div class="item-padding-none">
										<div class="item-inner">
											<div class="item-photo">
												<img src="<?= \common\component\image\Image::resize($value->product->image, 200, 200) ?>"
												     alt="" class="db w"/> <!--已售罄-->
												<?php if (!$value->product->getStockCount()) { ?><i
													class="item-tip iconfont">&#xe696;</i><?php } ?>
											</div>
											<div class="item-detail">
												<p class="row-two f12 mt2 mb2">
													<?= $value->product->description->name ?>
												</p>
												<span class="red">￥<?= $value->product->getPrice() ?></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<div id="item_2">
			<?php if ($floor_2_01) { ?>
				<div class="tit-- green">
					<span class="vm">米面</span>
				</div>
				<div class="row tc bg-wh">
					<?php foreach ($floor_2_01 as $value) { ?>
						<div class="col-2 p5 bdr bdb">
							<a href="<?= \yii\helpers\Url::to(['/product/index', 'shop_code' => $value->product->store_code, 'product_code' => $value->product->product_code]) ?>"
							   class="db w">
								<div class="item">
									<div class="item-padding-none">
										<div class="item-inner">
											<div class="item-photo">
												<img src="<?= \common\component\image\Image::resize($value->product->image, 200, 200) ?>"
												     alt="" class="db w"/> <!--已售罄-->
												<?php if (!$value->product->getStockCount()) { ?><i
													class="item-tip iconfont">&#xe696;</i><?php } ?>
											</div>
											<div class="item-detail">
												<p class="row-two f12 mt2 mb2">
													<?= $value->product->description->name ?>
												</p>
												<span class="red">￥<?= $value->product->getPrice() ?></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<div id="item_3">
			<?php if ($floor_3_01) { ?>
				<div class="tit-- green">
					<span class="vm">食用油</span>
				</div>
				<div class="row tc bg-wh">
					<?php foreach ($floor_3_01 as $value) { ?>
						<div class="col-2 p5 bdr bdb">
							<a href="<?= \yii\helpers\Url::to(['/product/index', 'shop_code' => $value->product->store_code, 'product_code' => $value->product->product_code]) ?>"
							   class="db w">
								<div class="item">
									<div class="item-padding-none">
										<div class="item-inner">
											<div class="item-photo">
												<img src="<?= \common\component\image\Image::resize($value->product->image, 200, 200) ?>"
												     alt="" class="db w"/> <!--已售罄-->
												<?php if (!$value->product->getStockCount()) { ?><i
													class="item-tip iconfont">&#xe696;</i><?php } ?>
											</div>
											<div class="item-detail">
												<p class="row-two f12 mt2 mb2">
													<?= $value->product->description->name ?>
												</p>
												<span class="red">￥<?= $value->product->getPrice() ?></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<div id="item_4">
			<?php if ($floor_4_01) { ?>
				<div class="tit-- green">
					<span class="vm">方便食品</span>
				</div>
				<div class="row tc bg-wh">
					<?php foreach ($floor_4_01 as $value) { ?>
						<div class="col-2 p5 bdr bdb">
							<a href="<?= \yii\helpers\Url::to(['/product/index', 'shop_code' => $value->product->store_code, 'product_code' => $value->product->product_code]) ?>"
							   class="db w">
								<div class="item">
									<div class="item-padding-none">
										<div class="item-inner">
											<div class="item-photo">
												<img src="<?= \common\component\image\Image::resize($value->product->image, 200, 200) ?>"
												     alt="" class="db w"/> <!--已售罄-->
												<?php if (!$value->product->getStockCount()) { ?><i
													class="item-tip iconfont">&#xe696;</i><?php } ?>
											</div>
											<div class="item-detail">
												<p class="row-two f12 mt2 mb2">
													<?= $value->product->description->name ?>
												</p>
												<span class="red">￥<?= $value->product->getPrice() ?></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<div id="item_5">
			<?php if ($floor_5_01) { ?>
				<div class="tit-- green">
					<span class="vm">挂面</span>
				</div>
				<div class="row tc bg-wh">
					<?php foreach ($floor_5_01 as $value) { ?>
						<div class="col-2 p5 bdr bdb">
							<a href="<?= \yii\helpers\Url::to(['/product/index', 'shop_code' => $value->product->store_code, 'product_code' => $value->product->product_code]) ?>"
							   class="db w">
								<div class="item">
									<div class="item-padding-none">
										<div class="item-inner">
											<div class="item-photo">
												<img src="<?= \common\component\image\Image::resize($value->product->image, 200, 200) ?>"
												     alt="" class="db w"/> <!--已售罄-->
												<?php if (!$value->product->getStockCount()) { ?><i
													class="item-tip iconfont">&#xe696;</i><?php } ?>
											</div>
											<div class="item-detail">
												<p class="row-two f12 mt2 mb2">
													<?= $value->product->description->name ?>
												</p>
												<span class="red">￥<?= $value->product->getPrice() ?></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<div id="item_6">
			<?php if ($floor_6_01) { ?>
				<div class="tit-- green">
					<span class="vm">调味</span>
				</div>
				<div class="row tc bg-wh">
					<?php foreach ($floor_6_01 as $value) { ?>
						<div class="col-2 p5 bdr bdb">
							<a href="<?= \yii\helpers\Url::to(['/product/index', 'shop_code' => $value->product->store_code, 'product_code' => $value->product->product_code]) ?>"
							   class="db w">
								<div class="item">
									<div class="item-padding-none">
										<div class="item-inner">
											<div class="item-photo">
												<img src="<?= \common\component\image\Image::resize($value->product->image, 200, 200) ?>"
												     alt="" class="db w"/> <!--已售罄-->
												<?php if (!$value->product->getStockCount()) { ?><i
													class="item-tip iconfont">&#xe696;</i><?php } ?>
											</div>
											<div class="item-detail">
												<p class="row-two f12 mt2 mb2">
													<?= $value->product->description->name ?>
												</p>
												<span class="red">￥<?= $value->product->getPrice() ?></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<div id="item_7">
			<?php if ($floor_7_01) { ?>
				<div class="tit-- green">
					<span class="vm">南北干货</span>
				</div>
				<div class="row tc bg-wh">
					<?php foreach ($floor_7_01 as $value) { ?>
						<div class="col-2 p5 bdr bdb">
							<a href="<?= \yii\helpers\Url::to(['/product/index', 'shop_code' => $value->product->store_code, 'product_code' => $value->product->product_code]) ?>"
							   class="db w">
								<div class="item">
									<div class="item-padding-none">
										<div class="item-inner">
											<div class="item-photo">
												<img src="<?= \common\component\image\Image::resize($value->product->image, 200, 200) ?>"
												     alt="" class="db w"/> <!--已售罄-->
												<?php if (!$value->product->getStockCount()) { ?><i
													class="item-tip iconfont">&#xe696;</i><?php } ?>
											</div>
											<div class="item-detail">
												<p class="row-two f12 mt2 mb2">
													<?= $value->product->description->name ?>
												</p>
												<span class="red">￥<?= $value->product->getPrice() ?></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<div id="item_8">
			<?php if ($floor_8_01) { ?>
				<div class="tit-- green">
					<span class="vm">杂粮</span>
				</div>
				<div class="row tc bg-wh">
					<?php foreach ($floor_8_01 as $value) { ?>
						<div class="col-2 p5 bdr bdb">
							<a href="<?= \yii\helpers\Url::to(['/product/index', 'shop_code' => $value->product->store_code, 'product_code' => $value->product->product_code]) ?>"
							   class="db w">
								<div class="item">
									<div class="item-padding-none">
										<div class="item-inner">
											<div class="item-photo">
												<img src="<?= \common\component\image\Image::resize($value->product->image, 200, 200) ?>"
												     alt="" class="db w"/> <!--已售罄-->
												<?php if (!$value->product->getStockCount()) { ?><i
													class="item-tip iconfont">&#xe696;</i><?php } ?>
											</div>
											<div class="item-detail">
												<p class="row-two f12 mt2 mb2">
													<?= $value->product->description->name ?>
												</p>
												<span class="red">￥<?= $value->product->getPrice() ?></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<div id="item_9">
			<?php if ($floor_9_01) { ?>
				<div class="tit-- green">
					<span class="vm">酱菜</span>
				</div>
				<div class="row tc bg-wh">
					<?php foreach ($floor_9_01 as $value) { ?>
						<div class="col-2 p5 bdr bdb">
							<a href="<?= \yii\helpers\Url::to(['/product/index', 'shop_code' => $value->product->store_code, 'product_code' => $value->product->product_code]) ?>"
							   class="db w">
								<div class="item">
									<div class="item-padding-none">
										<div class="item-inner">
											<div class="item-photo">
												<img src="<?= \common\component\image\Image::resize($value->product->image, 200, 200) ?>"
												     alt="" class="db w"/> <!--已售罄-->
												<?php if (!$value->product->getStockCount()) { ?><i
													class="item-tip iconfont">&#xe696;</i><?php } ?>
											</div>
											<div class="item-detail">
												<p class="row-two f12 mt2 mb2">
													<?= $value->product->description->name ?>
												</p>
												<span class="red">￥<?= $value->product->getPrice() ?></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
			<?=\h5\widgets\Block\Hongbao::widget(['code'=>'grain'])?>
		</div>
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
    var swiper_news = new Swiper('#swiper-container_news', {
    pagination: '.swiper-pagination-news',
    paginationClickable: true,
    spaceBetween: 0,
    centeredSlides: true,
    autoplay: 4000,
    autoplayDisableOnInteraction: false
    });
	/* 分类导航初始化行数，及显示隐藏 */
	$('.normal-items2').rowMore(1);
	$('#nav-menu').onePageNav({container:$(".content"),currentClass:"cur",scrollThreshold: 1,easing: 'swing',top:-80});
	$("#nav-fix").scrollFix({container:$(".content"),zIndex : 99999,distanceTop:45});
    Ad_Sys_Code();
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>