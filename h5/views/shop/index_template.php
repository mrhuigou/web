<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \common\component\image\Image;
/* @var $this yii\web\View */
$this->title =$model->name.'-首页';
?>
<!-- 分类导航 -->
<div class="shop-nav2 static bs-b">
    <div class="filter six filter-bd filter-red">
        <?php if($model){?>
            <?php if($model->categorys){?>
                    <?php foreach ($model->categorys as $category){?>
                        <a href="<?php echo \yii\helpers\Url::to(['shop/search','store_cate_code'=>$category->category_store_code,'shop_code'=>$model->store_code])?>" class="item"><?php echo $category->name?></a>
                <?php }?>
            <?php }?>
        <?php }?>

    </div>
</div>

<div class="q"></div>

<div class="shop-nav2 fixed bs-b" style="display: none;">
    <div class="shop-nav2-tit">
        <i class="iconfont fr arr-t"></i>
        <h2 class="f16 fb">全部分类</h2>
    </div>
    <div class="filter six filter-bd filter-red x">
        <?php if($model){?>
            <?php if($model->categorys){?>
                <?php foreach ($model->categorys as $category){?>
                    <a href="<?php echo \yii\helpers\Url::to(['shop/search','store_cate_code'=>$category->category_store_code,'shop_code'=>$model->store_code])?>" class="item"><?php echo $category->name?></a>
                <?php }?>
            <?php }?>
        <?php }?>

    </div>
    <i class="iconfont arr-b"></i>
</div>
<div class="mask" style="display: none;"></div>

<?php if($model->h5Theme && $model->h5Theme->info){?>
    <?php foreach($model->h5Theme->info as $row){ ?>
        <?php if($row->theme_column_type=='ADS'){
            if(!$row->info){
                continue;
            }
            ?>
            <div class="swiper-container mt5 mb5" id="swiper-container_banner">
                <div class="swiper-wrapper">
                    <?php foreach($row->info as $value){ ?>
                        <div class="swiper-slide">
                            <a href="<?=$value->url?>">
                                <img data-original="<?=Image::resize($value->image,640,266)?>" width="100%" class="w lazy db">
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-white"></div>
        <?php } ?>

<!-- 商品列表 -->
<!-- 特惠 -->
<div class="bg-wh mb10">
    <?php if($row->theme_column_type=='PRODUCT'){
        if(!$row->info){
            continue;
        }
        ?>
    <div class="tit--"><?=$row->name?></div>
    <div class="flex-col">
        <?php foreach($row->info as $value){ ?>
        <div class="flex-item-6 p5">
            <a href="<?=Url::to(['/product/index','product_base_id'=>$value->product->product_base_id],true)?>">
                <img src="<?=Image::resize($value->product->productBase->image,300,300)?>" class="w" /></a>
            <div class="p5">
                <p class="row-two mb5"><?=$value->product->description->name?></p>
                <p class="gray9 f12 row-one"><?=$value->product->description->meta_keyword?></p>
                <p class="mt5">
                    <span class="red2">￥<?=$value->product->productBase->price?></span>
                    <span class="gray9 del f12">￥<?=$value->product->productBase->price?></span>
                </p>
            </div>
        </div>
        <?php }?>

    </div>
    <a href="<?=$row->url?>" class="db tc red2 f12 p10">更多特惠 &gt;</a>
</div>
<?php } ?>
<?php } ?>
<?php } ?>
<!-- 推荐 -->

<script type="text/javascript">
    <?php
    $this->beginBlock('JS_IMAGE')
    ?>

    // 分类导航
    $.fn.scrollTar = function(tar){
        var t = tar.offset().top;
        var _this = $(this);

        function f(){
            if($(window).scrollTop() > t){
                _this.show();
            }else{
                _this.hide();
            }
        }

        f();
        $(window).scroll(function(){
            f();
        })
    }

    $(".shop-nav2.fixed").scrollTar($(".q"));


    $(".shop-nav2.fixed").find(".arr-b").click(function(){
        $(".shop-nav2.fixed").addClass("show").find(".filter").removeClass("x");
        $(".mask").show();
    });
    $(".mask, .arr-t").click(function(){
        $(".shop-nav2.fixed").removeClass("show").find(".filter").addClass("x");
        $(".mask").hide();
    });



    // 活动banner
    var swiper_banner = new Swiper('#swiper-container_banner', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        loop:true,
        spaceBetween: 0,
        centeredSlides: true,
        autoplay: 4000,
        autoplayDisableOnInteraction: false
    });

    <?php $this->endBlock() ?>
    <?php
    \yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['JS_IMAGE'],\yii\web\View::POS_READY);
    ?>
</script>
