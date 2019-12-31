<?php
if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $this->title = "智慧生活";
}else{
	$this->title = "每日惠购";
}
?>
<header class="header" >
    <?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){ ?>
        <a href="/site/index?sourcefrom=zhqd" class="header-left" id="show-notification">
            <img src="/images/zhqd_logo.png" width="110" style="margin-top: -6px;">
        </a>
        <div class="header-search clearfix pr" style="margin-right: 10px;">
            <form action="<?php echo \yii\helpers\Url::to(['/search/index'])?>" method="get" id="search_form">
                <input class="input-text s w" id="searchBox"  type="text" name="keyword"
                       value="<?= Yii::$app->request->get('keyword') ?>" autocomplete="off" tabindex="1" style="border: medium none;">
                <a href="javascript:void(0)" class="search-btn iconfont">&#xe601;</a>
            </form>
        </div>
    <?php }else{?>
    <a href="javascript:;" class="header-left" id="show-notification">
        <img src="/assets/images/logo2.png" width="110" style="margin-top: -6px;">
    </a>
    <!--20150609-->
    <div class="header-search clearfix pr">
        <form action="<?php echo \yii\helpers\Url::to(['/search/index'])?>" method="get" id="search_form">
            <input class="input-text s w" id="searchBox" type="text" name="keyword"
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
            <?php if(!\Yii::$app->session->get('source_from_agent_wx_xcx')){?>
            <li>
                <a href="javascript:;" class="share-guide">
                    <i class="iconfont vm mr5">&#xe644;</i>
                    <span class="vm">分享</span>
                </a>
            </li>
            <?php }?>
            <li>
                <a href="tel:4008556977">
                    <i class="iconfont vm mr5">&#xe65e;</i>
                    <span class="vm">客服</span>
                </a>
            </li>
        </ul>
    </div>
    <?php } ?>
</header>
<div class="content" >
<section class="veiwport " style="max-width: inherit;width: 32rem;height: auto;">
    <div id="swiper_content" style="max-width: inherit;width: 32rem;height: 13.3rem;overflow: hidden;"></div>
    <script id="swiper_content_tpl" type="text/html">
        <div class="swiper-container" id="swiper-container_banner">
            <div class="swiper-wrapper">
                 <% for(var i=from; i<=to; i++) {%>
                    <div class="swiper-slide" >
                        <a href="<%:=list[i].url%>">
                            <img
                                  data-original="<%:=list[i].image%>" class=" w lazy">
                        </a>
                    </div>
                <% } %>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-white swiper-pagination-banner"></div>
        </div>
    </script>
    <!--主导航-->
    <div class="nav2" >
	    <?php if($nav){?>
		    <?php foreach ($nav as $value){?>
                <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="item col-4">
                    <img  src="<?= \common\component\image\Image::resize($value->source_url, 100, 100) ?>" class="img-circle bd" style="width: 45px;height: 45px;overflow: hidden;">
                    <span><?= $value->title ?></span>
                </a>
		    <?php }?>
	    <?php }?>
    </div>
<?php // echo \h5\widgets\Block\News::widget()?>
    <!--爆款
    <a href="<?php //echo \yii\helpers\Url::to(['/page/index','page_id'=>'3340'])?>">
        <img class="w" src="../assets/images/skin/skin-0.jpg" />
    </a>-->
    <div class="jb" style="padding-bottom: 0px;">
        <a href="https://m.mrhuigou.com/page/2581.html" class="t"></a>
        <div class="tit1  redtit1">
            <h2>爆品专区<a class="fr f12 red mt2" href="<?php echo \yii\helpers\Url::to(['/page/index','page_id'=>2581])?>">更多&gt;&gt;</a>
            </h2>
        </div>
    <div id="hot_content" style="max-width: inherit;overflow: hidden;" class="pl5 pr5"></div>
    </div>
    <script id="hot_content_tpl" type="text/html">
    <div class="swiper-container" id="swiper-container_ad">
        <div class="swiper-wrapper swiper-container-no-flexbox">
            <div class="swiper-slide clearfix row">
            <% for(var i=from; i<=to; i++) {%>
                <a href="<%:=list[i].url%>" class="db col-3 " >
                    <img src="<%:=list[i].image%>"  class="fl" style="height: 10.4rem;width: 10.4rem;border-left: 0.125rem solid #eee;border-bottom: 0.23rem solid #eee;"">
                </a>
           <% if((i+1)%6==0 && (i+1)< to){ %>
            </div> <div class="swiper-slide clearfix row">
            <% } %>
            <% } %>
            </div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination swiper-pagination-ad" style="position: relative;"></div>
    </div>
    </script>
<!--    <div class="ts_1">-->
<!--        <div class="tit1 greentit1">-->
<!--            <h2>孕婴频道</h2>-->
<!--        </div>-->
<!--    </div>-->

<!--    --><?php //if ($ad_banner_1) { ?>
<!--        <ul class="pro-list23 pt10">-->
<!--            --><?php //foreach ($ad_banner_1 as $key => $value) { ?>
<!--                --><?php //if (in_array($value['advertise_media_type'],['PACK','IMAGE'])) { ?>
<!--                    <li class="clearfix mb5 pl5 pr5" style="overflow: hidden;">-->
<!--                        <a href="--><?//= \yii\helpers\Url::to($value->link_url, true) ?><!--">-->
<!--                            <img-->
<!--                                    data-original="--><?//= \common\component\image\Image::resize($value->source_url) ?><!--"-->
<!--                                    title="--><?php //echo $value->title; ?><!--" alt="--><?//= $value->title ?><!--"-->
<!--                                    class="db w lazy" >-->
<!--                        </a>-->
<!--                    </li>-->
<!--                --><?php //} ?>
<!--            --><?php //} ?>
<!--        </ul>-->
<!--    --><?php //} ?>

<?=\h5\widgets\IndexHot::widget()?>
    <!--广告位-->
    <!--限时抢购-->
    <div id="skill_content"></div>
    <script id="skill_content_tpl" type="text/html">
    <div class="tit1 clearfix">
        <h2 class="fl red fb">限时抢购</h2>
        <p class="fl countdown">
            <span class="hour_show">00</span>:<span class="minute_show">00</span>:<span class="second_show">00</span>
        </p>
        <a class="fr f12 red mt2" href="<?=\yii\helpers\Url::to(['/promotion/index','subject'=>'PANIC'])?>">更多&gt;&gt;</a>
    </div>
    <div class="swiper-container  " id="swiper-container-sales">
        <div class="swiper-pagination swiper-pagination-qg" style="position: relative;"></div>
        <div class="swiper-wrapper ">
            <% for(var i=from; i<=to; i++) {%>
            <div class="swiper-slide">
                <a href="<%:=list[i].url%>" class="db tc">
                    <img src="<%:=list[i].image%>" class="w lazy">
                    <p class="f12 lh200 whitebg row-two "><%:=list[i].name%></p>
                    <p class="red  fb f14 lh200 whitebg tc ">￥<%:=list[i].cur_price%></p>
                </a>
            </div>
            <% } %>
        </div>
    </div>
</script>
    <!--促销活动-->
    <?php if($ad_promotion){ ?>
        <div class="tit1 orgtit1">
            <h2>促销活动</h2>
        </div>
        <div class="row">
            <?php foreach(array_slice($ad_promotion,0,9) as $value){ ?>
                <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="col-3">
                    <img data-original="<?= \common\component\image\Image::resize($value->source_url, 210, 210) ?>" class="lazy" style="width: 10.5rem;height: 10.5rem;overflow: hidden;">
                </a>
            <?php } ?>
        </div>
    <?php }?>
    <?php if($ad_promotion_12){?>
    <div class="cx flex-col">
        <a href="https://m.mrhuigou.com/page/994.html" class="t"></a>
            <?php foreach($ad_promotion_12 as $value){ ?>
                <a class="flex-item-6" href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
                    <img src="<?= \common\component\image\Image::resize($value->source_url, 320, 190) ?>" class="w">
                </a>
            <?php } ?>
    </div>
    <?php } ?>

    <!--广告位-->
    <?php if ($ad_banner_2) { ?>
        <?php foreach ($ad_banner_2 as $value) { ?>
            <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
                <img  data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 156) ?>" class="db  lazy mt10" style="width:32rem;height: 7.8rem;overflow: hidden;">
            </a>
        <?php } ?>
    <?php } ?>

    <!--品牌特卖-->
    <?php if($ad_brand){ ?>
        <div class="tit1 orgtit1 clearfix">
            <h2 >品牌特卖</h2>
        </div>
        <div class="flex-col bd">
            <div class="flex-item-12">
                <div class="swiper-container" id="brand-sale">
                    <div class="swiper-wrapper swiper-container-no-flexbox">
                        <?php foreach($ad_brand as $value){?>
                            <div class="swiper-slide">
                                <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
                                    <img data-original="<?= \common\component\image\Image::resize($value->source_url, 400, 190) ?>" class=" w db lazy" >
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <style>
        .blueFilter .cur {
            border-bottom:2px solid green;
            color: green;
        }
        .blueFilter .cur a{
            color: green;
        }
    </style>
    <a name="category_menu"></a>
        <div id="menu_content" class="mt10 pr">
        <div class="menu-tab">
            <ul id="nav" class="filter blueFilter five clearfix mt10 top-list-tab tabs ">
                <li class="cur"><a href="#shengxian">生鲜</a></li>
                <li><a href="#jiushui">酒水</a></li>
<!--                <li><a href="#muying">母婴</a></li>-->
                <li><a href="#liangyou">粮油</a></li>
                <li><a href="#xihua">洗化</a></li>
                <li><a href="#xiushi">休食</a></li>
            </ul>
        </div>
        <div class="panels">
            <h2 class="tit">
                <span class="green">生鲜</span>
            </h2>
            <!--双十一-->
            <div class="panel" id="shengxian">
                <!--产品组-->
                <?php if ($fourthF_PRODUCT_ONE) { ?>
                    <ul class="pro-list23">
                        <?php foreach ($fourthF_PRODUCT_ONE as $key => $value) { ?>
                            <?php if (in_array($value['advertise_media_type'],['PACK','IMAGE'])) { ?>
                                <li class="clearfix mb5 pl5 pr5" style="width: 32rem;height: 12.5rem;overflow: hidden;">
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
                                        <img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 250) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="db w lazy" >
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
                <!--品牌两个广告-->
                <div class="row">
                    <?php if ($fourthF_BRAND_ONE) { ?>
                        <?php foreach ($fourthF_BRAND_ONE as $key => $value) { ?>
                            <?php if ($key < 3) { ?>
                                <div class="col-3">
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db p2">
                                        <img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="db  lazy" style="width: 10rem;height: 10rem;">
                                    </a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
                <!--品牌logo组-->
                <?php if ($fourthF_PLOGO_ONE) { ?>
                    <ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"
                        style="margin-top:0.4rem;">
                        <?php foreach ($fourthF_PLOGO_ONE as $key => $value) { ?>
                            <?php if ($key < 5) { ?>
                                <li>
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"><img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="lazy" ></a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
            <h2 class="tit">
                <span class="green">酒水</span>
            </h2>

            <div class="panel" id="jiushui">
                <!--产品组-->
                <?php if ($fourthF_PRODUCT_TWO) { ?>
                    <ul class="pro-list23">
                        <?php foreach ($fourthF_PRODUCT_TWO as $key => $value) { ?>
                            <?php if (in_array($value['advertise_media_type'],['PACK','IMAGE'])) { ?>
                                <li class="clearfix mb5 pl5 pr5" style="width: 32rem;height: 12.5rem;overflow: hidden;">
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
                                        <img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 250) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="db w lazy" >
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
                <!--品牌3个广告-->
                <div class="row">
                    <?php if ($fourthF_BRAND_TWO) { ?>
                        <?php foreach ($fourthF_BRAND_TWO as $key => $value) { ?>
                            <?php if ($key < 3) { ?>
                                <div class="col-3">
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db p2">
                                        <img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="db  lazy" style="width: 10rem;height: 10rem;">
                                    </a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
                <!--品牌logo组-->
                <?php if ($fourthF_PLOGO_TWO) { ?>
                    <ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"
                        style="margin-top:0.4rem;">
                        <?php foreach ($fourthF_PLOGO_TWO as $key => $value) { ?>
                            <?php if ($key < 5) { ?>
                                <li>
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"><img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="lazy" ></a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>

            <h2 class="tit">
                <span class="green">粮油</span>
            </h2>
            <div class="panel" id="liangyou">
                <!--产品组-->
                <?php if ($fourthF_PRODUCT_THREE) { ?>
                    <ul class="pro-list23">
                        <?php foreach ($fourthF_PRODUCT_THREE as $key => $value) { ?>
                            <?php if (in_array($value['advertise_media_type'],['PACK','IMAGE'])) { ?>
                                <li class="clearfix mb5 pl5 pr5" style="width: 32rem;height: 12.5rem;overflow: hidden;">
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
                                        <img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 250) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="db w lazy" >
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>

                <!--品牌3个广告-->
                <div class="row">

                    <?php if ($fourthF_BRAND_THREE) { ?>
                        <?php foreach ($fourthF_BRAND_THREE as $key => $value) { ?>
                            <?php if ($key < 3) { ?>
                                <div class="col-3">
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db p2">
                                        <img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="db  lazy" style="width: 10rem;height: 10rem;">
                                    </a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>


                </div>
                <!--品牌logo组-->
                <?php if ($fourthF_PLOGO_THREE) { ?>
                    <ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"
                        style="margin-top:0.4rem;">
                        <?php foreach ($fourthF_PLOGO_THREE as $key => $value) { ?>
                            <?php if ($key < 5) { ?>
                                <li>
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"><img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="lazy" ></a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
            <h2 class="tit">
                <span class="green">洗化</span>
            </h2>
            <div class="panel" id="xihua">
                <!--产品组-->
                <?php if ($fourthF_PRODUCT_FOUR) { ?>
                    <ul class="pro-list23">
                        <?php foreach ($fourthF_PRODUCT_FOUR as $key => $value) { ?>
                            <?php if (in_array($value['advertise_media_type'],['PACK','IMAGE'])) { ?>
                                <li class="clearfix mb5 pl5 pr5" style="width: 32rem;height: 12.5rem;overflow: hidden;">
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
                                        <img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 250) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="db w lazy" >
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>

                <!--品牌3个广告-->
<!--                <div class="row">-->
<!--                    --><?php //if ($fourthF_BRAND_FOUR) { ?>
<!--                        --><?php //foreach ($fourthF_BRAND_FOUR as $key => $value) { ?>
<!--                            --><?php //if ($key < 3) { ?>
<!--                                <div class="col-3">-->
<!--                                    <a href="--><?//= \yii\helpers\Url::to($value->link_url, true) ?><!--" class="db p2">-->
<!--                                        <img-->
<!--                                              data-original="--><?//= \common\component\image\Image::resize($value->source_url, 200, 200) ?><!--"-->
<!--                                            title="--><?php //echo $value->title; ?><!--" alt="--><?//= $value->title ?><!--"-->
<!--                                            class="db  lazy" style="width: 10rem;height: 10rem;">-->
<!--                                    </a>-->
<!--                                </div>-->
<!--                            --><?php //} ?>
<!--                        --><?php //} ?>
<!--                    --><?php //} ?>
<!--                </div>-->

                <!--品牌logo组-->
                <?php if ($fourthF_PLOGO_FOUR) { ?>
                    <ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"
                        style="margin-top:0.4rem;">
                        <?php foreach ($fourthF_PLOGO_FOUR as $key => $value) { ?>
                            <?php if ($key < 5) { ?>
                                <li>
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"><img
                                              data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="lazy" ></a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
            <h2 class="tit">
                <span class="green">休食</span>
            </h2>
            <div class="panel" id="xiushi">
                <!--产品组-->
                <?php if ($fourthF_PRODUCT_FIVE) { ?>
                    <ul class="pro-list23">
                        <?php foreach ($fourthF_PRODUCT_FIVE as $key => $value) { ?>
                            <?php if (in_array($value['advertise_media_type'],['PACK','IMAGE'])) { ?>
                                <li class="clearfix mb5 pl5 pr5" style="width: 32rem;height: 12.5rem;overflow: hidden;">
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
                                        <img
                                                data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 250) ?>"
                                                title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                                class="db w lazy" >
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
                <!--品牌3个广告-->
<!--                <div class="row">-->
<!---->
<!--                    --><?php //if ($fourthF_BRAND_FIVE) { ?>
<!--                        --><?php //foreach ($fourthF_BRAND_FIVE as $key => $value) { ?>
<!--                            --><?php //if ($key < 3) { ?>
<!--                                <div class="col-3">-->
<!--                                    <a href="--><?//= \yii\helpers\Url::to($value->link_url, true) ?><!--" class="db p2">-->
<!--                                        <img-->
<!--                                                data-original="--><?//= \common\component\image\Image::resize($value->source_url, 200, 200) ?><!--"-->
<!--                                                title="--><?php //echo $value->title; ?><!--" alt="--><?//= $value->title ?><!--"-->
<!--                                                class="db  lazy" style="width: 10rem;height: 10rem;">-->
<!--                                    </a>-->
<!--                                </div>-->
<!--                            --><?php //} ?>
<!--                        --><?php //} ?>
<!--                    --><?php //} ?>
<!---->
<!---->
<!--                </div>-->

                <!--品牌logo组-->
<!--                --><?php //if ($fourthF_PLOGO_FIVE) { ?>
<!--                    <ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"-->
<!--                        style="margin-top:0.4rem;">-->
<!--                        --><?php //foreach ($fourthF_PLOGO_FIVE as $key => $value) { ?>
<!--                            --><?php //if ($key < 5) { ?>
<!--                                <li>-->
<!--                                    <a href="--><?//= \yii\helpers\Url::to(\yii\helpers\Url::to($value->link_url, true), true) ?><!--"><img-->
<!--                                                data-original="--><?//= \common\component\image\Image::resize($value->source_url, 200, 200) ?><!--"-->
<!--                                                title="--><?php //echo $value->title; ?><!--" alt="--><?//= $value->title ?><!--"-->
<!--                                                class="lazy" ></a>-->
<!--                                </li>-->
<!--                            --><?php //} ?>
<!--                        --><?php //} ?>
<!--                    </ul>-->
<!--                --><?php //} ?>
            </div>

        </div>
</section>


    <?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){  ?>
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
<script>
<?php $this->beginBlock("JS") ?>
    var wx_xcx = <?php echo Yii::$app->session->get('source_from_agent_wx_xcx') ? 1:0  ?>;
    var host = document.domain;
    if(host.indexOf('mwx.') >=0){
        wx_xcx = 1;
    }else{
        wx_xcx = 0;
    }
    var swiper_content_tpl = $('#swiper_content_tpl').html();
    var source = getSourceParms();
    $.getJSON('<?php echo Yii::$app->params["API_URL"]?>/mall/v1/ad/index?code=H5-0F-SLIDE&wx_xcx='+wx_xcx+'&callback=?&'+source, function(result){
    if(!result.status){
        return;
    }

    var html= template(swiper_content_tpl, {list:result.data,from:0,to:(result.data.length-1)});

    $("#swiper_content").html(html);
    $("img.lazy").scrollLoading({container:$(".content")});
    var swiper_banner = new Swiper('#swiper-container_banner', {
    pagination: '.swiper-pagination-banner',
    paginationClickable: true,
    loop:true,
    spaceBetween: 0,
    centeredSlides: true,
    autoplay: 4000,
    autoplayDisableOnInteraction: false
    });
    });
    var hot_content_tpl = $('#hot_content_tpl').html();
    $.getJSON('<?php echo Yii::$app->params["API_URL"]?>/mall/v1/ad/index?code=H5-0F-AD&wx_xcx='+wx_xcx+'&callback=?&'+source, function(result){
    var html= template(hot_content_tpl, {list:result.data,from:0,to:(result.data.length-1)});
    $("#hot_content").html(html);
    $("img.lazy").scrollLoading({container:$(".content")});
    //爆款滑动
    var swiper_ad = new Swiper('#swiper-container_ad', {
    pagination: '.swiper-pagination-ad',
    paginationType: 'progress',
    paginationClickable: true,
    autoplay: 6000,
    loop:true,
    });
    });
    var skill_content_tpl = $('#skill_content_tpl').html();
    $.getJSON('<?php echo Yii::$app->params["API_URL"]?>/mall/v1/promotion/subject?subject=PANIC&wx_xcx='+wx_xcx+'&callback=?&'+source, function(result){
    var html= template(skill_content_tpl, {promotion:result.promotion,list:result.data,from:0,to:(result.data.length-1) });
    $("#skill_content").html(html);
    $("img.lazy").scrollLoading({container:$(".content")});
    //限时抢购滑动
    var sales = new Swiper('#swiper-container-sales', {
        pagination: '.swiper-pagination-qg',
        paginationType: 'progress',
        loop:true,
        slidesPerView: 4,
        spaceBetween: 5,
        autoplay: 6000,
        paginationType: 'progress',
        autoplayDisableOnInteraction: false
    });
   timer(result.promotion.timestamp,$("#skill_content"));
    });
    //品牌特卖滑动
    var brandSale = new Swiper('#brand-sale', {
    slidesPerView: "auto",
    spaceBetween: 5,
    loop:true,
    autoplay: 6000,
    autoplayDisableOnInteraction: false
    });



    if($.cookie('home_scroll')){
    $(".content").scrollTop($.cookie('home_scroll'));
    }
    $(".content").on('scroll',function(){
        var scroll=$(this).scrollTop();
        $.cookie('home_scroll', scroll);
    });
/* 消息列表 */
$(".dropdown1").dropdown();
$.backtop(".content");

Ad_Sys_Code();
<?php $this->endBlock() ?>
</script>

<?php
$this->registerJs($this->blocks['JS'], \yii\web\View::POS_READY);
$this->registerJsFile('https://g.alicdn.com/opensearch/opensearch-console/0.16.0/scripts/jquery-ui-1.10.2.js',['depends'=>['h5\assets\AppAsset'],'position' => \yii\web\View::POS_END]);
$this->registerCssFile('https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css',['depends'=>['h5\assets\AppAsset'],'position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('/assets/script/auto_product_name.js',['depends'=>['h5\assets\AppAsset'],'position' => \yii\web\View::POS_END]);
?>
<?= h5\widgets\MainMenu::widget(); ?>
<?=\h5\widgets\Block\Share::widget();?>
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
        'image' => Yii::$app->request->getHostInfo().'/assets/images/logo_300x300.png'
    ];
}
?>
<?=\h5\widgets\Tools\Share::widget([
	'data'=>$data
])?>

