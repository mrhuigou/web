<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/20
 * Time: 15:13
 */
$this->title = "生鲜专区";
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
        <?php if($nav){ ?>
            <div class="row nav2 mb5">
                <?php foreach ($nav as $value){?>
                <a href="<?= \yii\helpers\Url::to($value->link_url) ?>" class="item col-5 db " >
                    <img src="<?= \common\component\image\Image::resize($value->source_url,100,100) ?>" class=" img-circle bd" style="width: 60px;height: 60px;">
                    <span><?= $value->title ?></span>
                </a>
                <?php } ?>
            </div>
        <?php } ?>
        <!--抢手货-->
	    <?php if ($ad_2) { ?>
            <div class="tit1 tit1-red">
                <h2>抢手货</h2>
            </div>
            <div class="row">
			    <?php foreach ($ad_2 as $value) { ?>
                    <div class="col-2">
                        <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                            <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                        </a>
                    </div>
			    <?php } ?>
            </div>
	    <?php } ?>
        <!-- 优选 -->
		<?php if ($ad_1) { ?>
            <div class="tit1 tit1-red">
                <h2>精品推荐</h2>
            </div>
            <div class="row">
				<?php foreach ($ad_1 as $value) { ?>
                    <div class="mb5">
                        <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                            <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                        </a>
                    </div>
				<?php } ?>
            </div>
		<?php } ?>
        <!-- 点击更多展开更多优选-->


        <!-- 精选活鲜 -->
        <div class="tit-- green">
           <span class="vm">水果蔬菜</span>
        </div>
	    <?php if ($floor_1_01) { ?>
	    <?php foreach ($floor_1_01 as $value) { ?>
                <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w mb5">
                </a>
		    <?php } ?>
	    <?php } ?>
	    <?php if ($floor_1_02) { ?>
        <div class="row tc bg-wh">
	        <?php foreach ($floor_1_02 as $value) { ?>
            <div class="col-4 p5 bdr bdb">
                 <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="db w">
                <div class="item">
                    <div class="item-padding-none">
                        <div class="item-inner">
                            <div class="item-photo">
                                    <img src="<?= \common\component\image\Image::resize($value->product->image,200,200) ?>" alt="" class="db w" /> <!--已售罄-->
                                <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                            </div>
                            <div class="item-detail">
                                <p class="row-two f12 mt2 mb2">
		                            <?=$value->product->description->name?>
                                </p>
                                <span class="red">￥<?=$value->product->getPrice()?></span>
                            </div>
                        </div>
                    </div>
                </div>
                 </a>
            </div>
	        <?php } ?>
        </div>
	    <?php } ?>
        <!-- 禽肉水产 -->
        <div class="tit-- green">
            <span class="vm">禽肉水产</span>
        </div>
	    <?php if ($floor_2_01) { ?>
		    <?php foreach ($floor_2_01 as $value) { ?>
                <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w mb5">
                </a>
		    <?php } ?>
	    <?php } ?>
	    <?php if ($floor_2_02) { ?>
            <div class="row tc bg-wh">
			    <?php foreach ($floor_2_02 as $value) { ?>
                    <div class="col-4 p5 bdr bdb">
                        <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="db w">
                            <div class="item">
                                <div class="item-padding-none">
                                    <div class="item-inner">
                                        <div class="item-photo">
                                            <img src="<?= \common\component\image\Image::resize($value->product->image,200,200) ?>" alt="" class="db w" /> <!--已售罄-->
										    <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                                        </div>
                                        <div class="item-detail">
                                            <p class="row-two f12 mt2 mb2">
											    <?=$value->product->description->name?>
                                            </p>
                                            <span class="red">￥<?=$value->product->getPrice()?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
			    <?php } ?>
            </div>
	    <?php } ?>
        <!-- 蛋奶 -->
        <div class="tit-- green">
            <span class="vm">蛋奶</span>
        </div>
	    <?php if ($floor_3_01) { ?>
		    <?php foreach ($floor_3_01 as $value) { ?>
                <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w mb5">
                </a>
		    <?php } ?>
	    <?php } ?>
	    <?php if ($floor_3_02) { ?>
            <div class="row tc bg-wh">
			    <?php foreach ($floor_3_02 as $value) { ?>
                    <div class="col-4 p5 bdr bdb">
                        <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="db w">
                            <div class="item">
                                <div class="item-padding-none">
                                    <div class="item-inner">
                                        <div class="item-photo">
                                            <img src="<?= \common\component\image\Image::resize($value->product->image,200,200) ?>" alt="" class="db w" /> <!--已售罄-->
										    <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                                        </div>
                                        <div class="item-detail">
                                            <p class="row-two f12 mt2 mb2">
											    <?=$value->product->description->name?>
                                            </p>
                                            <span class="red">￥<?=$value->product->getPrice()?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
			    <?php } ?>
            </div>
	    <?php } ?>
        <!-- 冷冻速食 -->
        <div class="tit-- green">
            <span class="vm">冷冻速食</span>
        </div>
	    <?php if ($floor_4_01) { ?>
		    <?php foreach ($floor_4_01 as $value) { ?>
                <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w mb5">
                </a>
		    <?php } ?>
	    <?php } ?>
	    <?php if ($floor_4_02) { ?>
            <div class="row tc bg-wh">
			    <?php foreach ($floor_4_02 as $value) { ?>
                    <div class="col-4 p5 bdr bdb">
                        <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="db w">
                            <div class="item">
                                <div class="item-padding-none">
                                    <div class="item-inner">
                                        <div class="item-photo">
                                            <img src="<?= \common\component\image\Image::resize($value->product->image,200,200) ?>" alt="" class="db w" /> <!--已售罄-->
										    <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                                        </div>
                                        <div class="item-detail">
                                            <p class="row-two f12 mt2 mb2">
											    <?=$value->product->description->name?>
                                            </p>
                                            <span class="red">￥<?=$value->product->getPrice()?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
			    <?php } ?>
            </div>
	    <?php } ?>
        <!-- 糕点熟食 -->
        <div class="tit-- green">
            <span class="vm">糕点熟食</span>
        </div>
	    <?php if ($floor_5_01) { ?>
		    <?php foreach ($floor_5_01 as $value) { ?>
                <a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w mb5">
                </a>
		    <?php } ?>
	    <?php } ?>
	    <?php if ($floor_5_02) { ?>
            <div class="row tc bg-wh">
			    <?php foreach ($floor_5_02 as $value) { ?>
                    <div class="col-4 p5 bdr bdb">
                        <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="db w">
                            <div class="item">
                                <div class="item-padding-none">
                                    <div class="item-inner">
                                        <div class="item-photo">
                                            <img src="<?= \common\component\image\Image::resize($value->product->image,200,200) ?>" alt="" class="db w" /> <!--已售罄-->
										    <?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                                        </div>
                                        <div class="item-detail">
                                            <p class="row-two f12 mt2 mb2">
											    <?=$value->product->description->name?>
                                            </p>
                                            <span class="red">￥<?=$value->product->getPrice()?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
			    <?php } ?>
            </div>
	    <?php } ?>
	    <?=\h5\widgets\Block\Hongbao::widget(['code'=>'fresh'])?>
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
