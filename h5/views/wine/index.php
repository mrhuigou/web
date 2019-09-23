<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 11:15
 */
$this->title = "酒水专区";
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
        <div style="background: url(/images/wine_bg.jpg) no-repeat top center;background-size: 100%; background-color: #9FDA4A;">
            <!-- 活动焦点图 -->
			<?php if ($swiper) { ?>
                <div class="swiper-container" id="swiper-container_banner">
                    <div class="swiper-wrapper">
						<?php foreach ($swiper as $value) { ?>
                            <div class="swiper-slide">
                                <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>"
                                         class=" w ">
                                </a>
                            </div>
						<?php } ?>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination swiper-pagination-white swiper-pagination-banner"></div>
                </div>
			<?php } ?>
			<?php if ($nav) { ?>
                <div class="row nav2 mb5" style="background-color:transparent;">
					<?php foreach ($nav as $value) { ?>
                        <a href="<?= \yii\helpers\Url::to($value->link_url) ?>" class="item col-5 db white">
                            <img src="<?= \common\component\image\Image::resize($value->source_url, 100, 100) ?>"
                                 class=" img-circle bd" style="width: 50px;height: 50px; opacity: 0.9;border: none;">
                            <span><?= $value->title ?></span>
                        </a>
					<?php } ?>
                </div>
			<?php } ?>

            <!-- 优选 -->
			<?php if ($ad_1) { ?>
				<?php foreach ($ad_1 as $value) { ?>
                    <div class="w mb5">
                        <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                            <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                        </a>
                    </div>
				<?php } ?>
			<?php } ?>
        </div>
		<?= \h5\widgets\Block\News::widget() ?>
		<?php if ($ad_2) { ?>
            <div class="row mb5">
				<?php foreach ($ad_2 as $value) { ?>
                    <div class="col-3">
                        <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                            <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                        </a>
                    </div>
				<?php } ?>
            </div>
		<?php } ?>
		<?php if ($ad_3) { ?>
			<?php foreach ($ad_3 as $value) { ?>
                <div class="w mb5">
                    <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                    </a>
                </div>
			<?php } ?>
		<?php } ?>
        <div class="bg-wh w bs-b" id="nav-fix">
        <div class="normal-items y normal-items2 row-more" id="nav-menu">
                <span class="cur item"><a  href="#item_1">爆品精选</a></span>
                <span class="item"><a  href="#item_2">啤酒</a></span>
                <span class="item"><a  href="#item_3">水</a></span>
                <span class="item"><a  href="#item_4">汽水饮料</a></span>
                <span class="item"><a  href="#item_5">果蔬汁</a></span>
                <span class="item"><a  href="#item_6">黄酒保健</a></span>
                <span class="item"><a  href="#item_7">葡萄酒</a></span>
                <span class="item"><a  href="#item_8">白酒</a></span>
                <span class="item"><a  href="#item_9">功能饮料</a></span>
                <span class="item"><a  href="#item_10">茶饮</a></span>
                <span class="item"><a  href="#item_11">洋酒</a></span>
                <i class="more iconfont" style="display: none;">&#xe60a;</i>
        </div>
        </div>
        <div id="item_1">
			<?php if ($floor_1_01) { ?>
                <div class="tit-- green">
                    <span class="vm">爆品精选</span>
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
                    <span class="vm">啤酒</span>
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
                    <span class="vm">水</span>
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
                    <span class="vm">汽水饮料</span>
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
                    <span class="vm">果蔬汁</span>
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
                    <span class="vm">黄酒保健酒</span>
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
                    <span class="vm">葡萄酒</span>
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
                    <span class="vm">白酒</span>
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
                    <span class="vm">功能饮料</span>
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
        </div>
        <div id="item_10">
			<?php if ($floor_10_01) { ?>
                <div class="tit-- green">
                    <span class="vm">茶饮</span>
                </div>
                <div class="row tc bg-wh">
					<?php foreach ($floor_10_01 as $value) { ?>
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
        <div id="item_11">
			<?php if ($floor_11_01) { ?>
                <div class="tit-- green">
                    <span class="vm">洋酒</span>
                </div>
                <div class="row tc bg-wh">
					<?php foreach ($floor_11_01 as $value) { ?>
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
	        <?=\h5\widgets\Block\Hongbao::widget(['code'=>'wine'])?>
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
/* 分类导航初始化行数，及显示隐藏 */
$('.normal-items2').rowMore(1);
$('#nav-menu').onePageNav({container:$(".content"),currentClass:"cur",scrollThreshold: 1,easing: 'swing',top:-80});
$("#nav-fix").scrollFix({container:$(".content"),zIndex : 99999,distanceTop:45});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>
