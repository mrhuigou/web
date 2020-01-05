<?php
$this->title = "每日惠购";
?>
    <header class="header" >
        <a href="javascript:;" class="header-left" id="show-notification">
            <img src="/assets/images/logo2.png" width="110" style="margin-top: -6px;">
        </a>
        <!--20150609-->
        <div class="header-search clearfix pr">
            <form action="<?php echo \yii\helpers\Url::to(['/search/index'])?>" method="get" id="search_form">
                <input class="input-text s w" type="text" name="keyword"
                       value="<?= Yii::$app->request->get('keyword') ?>" autocomplete="off" tabindex="1" style="border: medium none;">
                <a href="javascript:void(0)" class="search-btn iconfont">&#xe601;</a>
            </form>
        </div>
        <!--消息-->
        <div class="dropdown dropdown1">
            <div class="pa-rt tc w50 dropdown-t" >
                <i class="iconfont green">&#xe670;</i>
                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->messageCount > 0) { ?>
                    <em class="dots-o pa-tr r10 t10"></em>
                <?php }?>
            </div>
            <ul class="dropdown-c r0 lh180" style="display: none;">
                <li>
                    <a href="/news/list">
                        <i class="iconfont vm mr5">&#xe649;</i>
                        <span class="vm">消息</span>
                        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->messageCount > 0) { ?>
                            <em class="dots-o pa-tr r10 t10"></em>
                        <?php }?>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="share-guide">
                        <i class="iconfont vm mr5">&#xe644;</i>
                        <span class="vm">分享</span>
                    </a>
                </li>
                <li>
                    <a href="tel:4008556977">
                        <i class="iconfont vm mr5">&#xe65e;</i>
                        <span class="vm">客服</span>
                    </a>
                </li>
            </ul>
        </div>
    </header>
    <article>
        <div class="content">

            <section class="veiwport " style="max-width: inherit;width: 32rem;height: auto;">

                <!-- 幻灯 -->
                <?php if($slide){?>


                    <div class="swiper-container" id="swiper-container_banner">
                        <div class="swiper-wrapper">
                          <?php foreach ($slide as $value){?>
                            <div class="swiper-slide">
                                <a href="<?= $value->link_url ?>">
                                    <img
                                        src="<?= \common\component\image\Image::resize($value->source_url) ?>"
                                        data-original="<?= \common\component\image\Image::resize($value->source_url) ?>" class=" w lazy">
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination swiper-pagination-white swiper-pagination-banner"></div>
                    </div>

                <?php }?>
                <!--妈妈必读-->
                <?=\h5\widgets\Block\News::widget(['category_id'=>2]);?>

                <!-- 妈咪必购 -->
                <?php if($all_show){?>
                <?php if($des1){?>
                <div>
                    <div class="tit2">
                        <h2>妈咪最爱</h2>
                    </div>
                    <div id="hot_content" style="max-width: inherit;overflow: hidden;" class="pl5 pr5"></div>
                </div>

                    <div class="swiper-container" id="swiper-container_ad">
                        <div class="swiper-wrapper swiper-container-no-flexbox">
                            <div class="swiper-slide clearfix row">
                                <?php foreach ($des1 as $key => $value){?>
                                    <a href="<?= $value->link_url ?>" class="flex-item-4 p1">
                                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                                    </a>

                                    <?php if(($key+1)%6 ==0 && $key+1 < count($des1)){?>
                                        </div> <div class="swiper-slide clearfix row">
                                    <?php }?>
                                <?php }?>
                            </div>
                        </div>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination swiper-pagination-ad" style="position: relative;"></div>
                    </div>
                <?php }?>


                <?php if($brand){?>
                <div class="tit2">
                    <h2>品牌钜惠  <a class="fr f12 red mt2 pdr5" href="<?=\yii\helpers\Url::to(['/baby/brands'])?>">更多&gt;&gt;</a></h2>
                </div>
                    <div class="row nav2 mb5 mt5">
                        <?php foreach ($brand as $value){?>
                            <div class="item col-5 p5" >
                                <a href="<?= $value->link_url ?>" >
                                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="img-circle bd" style="height:50px;width: 50px;">
                                </a>
                            </div>
                        <?php }?>
                    </div>
                <?php } ?>
                <?php }?>
                <?php if($des2){?>
                <!-- 品类钜惠 -->
                <div class="tit2">
                    <h2>妈咪必购</h2>
                </div>
                <div class="flex-col">
                    <?php foreach ($des2 as $value){?>

                        <div class="flex-item-6 p2">
                            <a href="<?= $value->link_url ?>">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                            </a>
                        </div>
                    <?php }?>
                </div>
                <?php }?>

                <?php if($des3){?>
                <!-- 宝宝成长之路 -->
                <div class="tit2">
                    <h2>宝宝成长之路</h2>
                </div>
                <div class="oh">
                    <div class="flex-col" style="margin: 0 -1px;">
                        <?php foreach ($des3 as $value){ ?>
                        <div class="flex-item-6 p1">
                            <a href="<?= $value->link_url ?>" class="w">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                            </a>

                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php }?>

                <?php if($all_show){?>
                <!--特惠专享-->
                <div class="tit2">
                    <h2 class="dib">特惠专享</h2><span class="f12 gray9"> | 大牌来袭</span>
                </div>

                <div>
                    <?php if($f11){?>
                        <?php foreach ($f11 as $value){?>
                        <a href="<?= $value->link_url ?>">
                            <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                        </a>
                    <?php }?>
                    <?php }?>
                </div>
                <?php if($f12){?>
                <div class="flex-col bg-wh mb10">
                   <?php foreach ($f12 as $value){?>
                       <?php $value->getInfo();?>
                    <a href="<?= $value->link_url ?>" class="flex-item-3 pr">
                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                        <div class="p5 tc">
                            <p class="row-two f12"><?=$value->product ? $value->product->description->name : '错误数据'?></p>
                            <span class="red">￥<?=$value->product ? $value->product->getPrice() : '错误数据';?></span>
                        </div>
                    </a>
                    <?php }?>

                </div>
                <?php }?>

                <div>
                    <?php if($f21){?>
                        <?php foreach ($f21 as $value){?>
                            <a href="<?= $value->link_url ?>">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                            </a>
                        <?php }?>
                    <?php }?>
                </div>
                <?php if($f22){?>
                    <div class="flex-col bg-wh mb10">
                        <?php foreach ($f22 as $value){?>
                            <?php $value->getInfo();?>
                            <a href="<?= $value->link_url ?>" class="flex-item-3 pr">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                                <div class="p5 tc">
                                    <p class="row-two f12"><?=$value->product ? $value->product->description->name : '错误数据'?></p>
                                    <span class="red">￥<?=$value->product ? $value->product->getPrice() : '错误数据';?></span>
                                </div>
                            </a>
                        <?php }?>

                    </div>
                <?php }?>
                <div>
                    <?php if($f31){?>
                        <?php foreach ($f31 as $value){?>
                            <a href="<?= $value->link_url ?>">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                            </a>
                        <?php }?>
                    <?php }?>
                </div>
                <?php if($f32){?>
                    <div class="flex-col bg-wh mb10">
                        <?php foreach ($f32 as $value){?>
                            <?php $value->getInfo();?>
                            <a href="<?= $value->link_url ?>" class="flex-item-3 pr">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                                <div class="p5 tc">
                                    <p class="row-two f12"><?=$value->product ? $value->product->description->name : '错误数据'?></p>
                                    <span class="red">￥<?=$value->product ? $value->product->getPrice() : '错误数据';?></span>
                                </div>
                            </a>
                        <?php }?>

                    </div>
                <?php }?>
                <div>
                <?php if($f41){?>
                    <?php foreach ($f41 as $value){?>
                        <a href="<?= $value->link_url ?>">
                            <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                        </a>
                    <?php }?>
                <?php }?>
        </div>
        <?php if($f42){?>
            <div class="flex-col bg-wh mb10">
                <?php foreach ($f42 as $value){?>
                    <?php $value->getInfo();?>
                    <a href="<?= $value->link_url ?>" class="flex-item-3 pr">
                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                        <div class="p5 tc">
                            <p class="row-two f12"><?=$value->product ? $value->product->description->name : '错误数据'?></p>
                            <span class="red">￥<?=$value->product ? $value->product->getPrice() : '错误数据';?></span>
                        </div>
                    </a>
                <?php }?>

            </div>
        <?php }?>
                <div>
        <?php if($f51){?>
            <?php foreach ($f51 as $value){?>
                <a href="<?= $value->link_url ?>">
                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                </a>
            <?php }?>
        <?php }?>
        </div>
        <?php if($f52){?>
            <div class="flex-col bg-wh mb10">
                <?php foreach ($f52 as $value){?>
                    <?php $value->getInfo();?>
                    <a href="<?= $value->link_url ?>" class="flex-item-3 pr">
                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                        <div class="p5 tc">
                            <p class="row-two f12"><?=$value->product ? $value->product->description->name : '错误数据'?></p>
                            <span class="red">￥<?=$value->product ? $value->product->getPrice() : '错误数据';?></span>
                        </div>
                    </a>
                <?php }?>

            </div>
        <?php }?>
                <div>
        <?php if($f61){?>
            <?php foreach ($f61 as $value){?>
                <a href="<?= $value->link_url ?>">
                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                </a>
            <?php }?>
        <?php }?>
        </div>
        <?php if($f62){?>
            <div class="flex-col bg-wh mb10">
                <?php foreach ($f62 as $value){?>
                    <?php $value->getInfo();?>
                    <a href="<?= $value->link_url ?>" class="flex-item-3 pr">
                        <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                        <div class="p5 tc">
                            <p class="row-two f12"><?=$value->product ? $value->product->description->name : '错误数据'?></p>
                            <span class="red">￥<?=$value->product ? $value->product->getPrice() : '错误数据';?></span>
                        </div>
                    </a>
                <?php }?>

            </div>
        <?php }?>
                    <div>
                        <?php if($f71){?>
                            <?php foreach ($f71 as $value){?>
                                <a href="<?= $value->link_url ?>">
                                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                                </a>
                            <?php }?>
                        <?php }?>
                    </div>
                    <?php if($f72){?>
                        <div class="flex-col bg-wh mb10">
                            <?php foreach ($f72 as $value){?>
                                <?php $value->getInfo();?>
                                <a href="<?= $value->link_url ?>" class="flex-item-3 pr">
                                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                                    <div class="p5 tc">
                                        <p class="row-two f12"><?=$value->product ? $value->product->description->name : '错误数据'?></p>
                                        <span class="red">￥<?=$value->product ? $value->product->getPrice() : '错误数据';?></span>
                                    </div>
                                </a>
                            <?php }?>

                        </div>
                    <?php }?>
                <?php }?>

                <div>
                    <?php if($des5){?>
                        <?php foreach ($des5 as $value){?>
                            <a href="<?= $value->link_url ?>">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                            </a>
                        <?php }?>
                    <?php }?>
                </div>
                <?php if($des6){?>
                    <div>
                        <div class="tit2">
                            <h2>妈咪最爱</h2>
                        </div>
                        <div id="hot_content" style="max-width: inherit;overflow: hidden;" class="pl5 pr5"></div>
                    </div>

                    <div class="swiper-container" id="swiper-container_ad2">
                        <div class="swiper-wrapper swiper-container-no-flexbox">
                            <div class="swiper-slide clearfix row">
                                <?php foreach ($des6 as $key => $value){?>
                                <a href="javascript:void(0);" class="flex-item-4 p1 showpop">
                                    <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w">
                                </a>

                                <?php if(($key+1)%6 ==0 && $key+1 < count($des6)){?>
                            </div> <div class="swiper-slide clearfix row">
                                <?php }?>
                                <?php }?>
                            </div>
                        </div>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination swiper-pagination-ad2" style="position: relative;"></div>
                    </div>
                <?php }?>

            </section>

            <?php if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){ ?>
                <footer class="gray9 tc p10 f14">
                    客服热线：0532-55729957
                </footer>
            <?php }else{ ?>
                <footer class="gray9 tc p10 f14">
                    Copyright©2015每日惠购 版权所有<br>
                    客服热线：0532-55729957
                </footer>
            <?php }?>
        </div>
    </article>
<script type="text/javascript">
    <?php $this->beginBlock("JS") ?>
    jQuery(document).ready(function() {
        /* 新闻播放 */
//
        var swiper_banner = new Swiper('#swiper-container_banner', {
            pagination: '.swiper-pagination-banner',
            paginationClickable: true,
            loop: true,
            spaceBetween: 0,
            centeredSlides: true,
            autoplay: 4000,
            autoplayDisableOnInteraction: false
        });

        //滑动
        var brandSale = new Swiper('#brand-sale', {
            slidesPerView: "auto",
            spaceBetween: 5,
            loop: true,
            autoplay: 6000,
            autoplayDisableOnInteraction: false
        });



        if ($.cookie('home_scroll')) {
            $(".content").scrollTop($.cookie('home_scroll'));
        }
        $(".content").on('scroll', function() {
            var scroll = $(this).scrollTop();
            $.cookie('home_scroll', scroll);
        });
        /* 消息列表 */
        $(".dropdown-t").dropdown();
        $.backtop(".content");

        /* 妈咪必购 */

            $("img.lazy").scrollLoading({container:$(".content")});
            //滑动
            var swiper_ad = new Swiper('#swiper-container_ad', {
                pagination: '.swiper-pagination-ad',
                paginationType: 'progress',
                paginationClickable: true,
                autoplay: 6000,
                loop:true,
            });

        //
        var swiper_ad = new Swiper('#swiper-container_ad2', {
            pagination: '.swiper-pagination-ad2',
            paginationType: 'progress',
            paginationClickable: true,
            autoplay: 6000,
            loop:true,
        });
        $(".showpop").bind('click',function () {

            $.showLoading();
            Ad_Sys_Code('H5-2LMY-TC01',1);
          //  $.hideLoading();
        });

    });


    <?php $this->endBlock() ?>
</script>
    <?php
    $this->registerJs($this->blocks['JS'], \yii\web\View::POS_READY);
    ?>
    <?= h5\widgets\MainMenu::widget(); ?>
<!--    --><?//=\h5\widgets\Block\Share::widget();?>
    <?php
     if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
        $data = [
            'title' => '遇到好东西，总想分享给最亲爱的你。',
            'desc' => "口袋超市，物美价廉，当日订单，当日送达。",
            'link' => Yii::$app->request->getAbsoluteUrl(),
            'image' => Yii::$app->request->getHostInfo().'/assets/images/zhqd/logo_300x300.jpeg'
        ];
    }else{
        $data = [
            'title' => '遇到好东西，总想分享给最亲爱的你。',
            'desc' => "每日惠购，物美价廉，当日订单，当日送达。",
            'link' => Yii::$app->request->getAbsoluteUrl(),
            'image' => Yii::$app->request->getHostInfo().'/assets/images/mrhuigou_logo.jpg'
        ];
    }
    ?>
    <?=\h5\widgets\Tools\Share::widget([
        'data'=>$data
    ]);?>
