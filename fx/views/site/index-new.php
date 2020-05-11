<?php
if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $this->title = "智慧生活";
}else{
	$this->title = "每日惠购-一起团";
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
        <img src="/assets/images/logo2.jpg" width="110" style="margin-top: -6px;">
    </a>
    <!--20150609-->
<!--    <div class="header-search clearfix pr">-->
<!--        <form action="--><?php //echo \yii\helpers\Url::to(['/search/index'])?><!--" method="get" id="search_form">-->
<!--            <input class="input-text s w" id="searchBox" type="text" name="keyword"-->
<!--                   value="--><?//= Yii::$app->request->get('keyword') ?><!--" autocomplete="off" tabindex="1" style="border: medium none;">-->
<!--            <a href="javascript:void(0)" class="search-btn iconfont">&#xe601;</a>-->
<!--        </form>-->
<!--    </div>-->
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
                <a href="tel:400-968-9870">
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
    <div id="swiper_content" style="max-width: inherit;width: 32rem;height: 13.3rem;overflow: hidden;display: none"></div>
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




    <?=\fx\widgets\IndexHot::widget()?>
    <?php  echo \fx\widgets\Block\News::widget()?>
    <?php if ($ad_banner_1) { ?>
        <div class="ts_1" style="display: none">
            <div class="tit1 greentit1">
                <!--            <h2>孕婴频道</h2>-->
                <h2>特别推荐</h2>
            </div>
        </div>
        <ul class="pro-list23 pt10">
            <?php foreach ($ad_banner_1 as $key => $value) { ?>
                    <li class="clearfix mb5 pl5 pr5" style="overflow: hidden;">
                        <a href="<?= 'https://m.mrhuigou.com/page/3556.html' ?>">
                            <img
                                    data-original="<?= \common\component\image\Image::resize($value->source_url) ?>"
                                    title="<?php echo $value->name; ?>" alt="<?= $value->name ?>"
                                    class="db w lazy" >
                        </a>
                    </li>
            <?php } ?>
        </ul>
    <?php } ?>


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
    
    <!--分销方案商品列表-->
    <?php  echo \fx\widgets\Affiliate\AffiliatePlan::widget(['plan_type_code'=>'DEFAULT'])?>
</section>


    <?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){  ?>
        <footer class="gray9 tc p10 f14">
            客服热线：400-968-9870
        </footer>
    <?php }else{ ?>
        <footer class="gray9 tc p10 f14">
            Copyright©2015每日惠购 版权所有<br>
            客服热线：400-968-9870
            <div>
                <a href="http://www.beian.gov.cn/portal/registerSystemInfo?spm=5176.12825654.7y9jhqsfz.112.e9392c4aykru4M&amp;aly_as=U0MWfk4s" target="_blank" data-spm-anchor-id="5176.12825654.7y9jhqsfz.111"><img data-src="//gw.alicdn.com/tfs/TB1GxwdSXXXXXa.aXXXXXXXXXXX-65-70.gif" style="width: 20px;" src="//gw.alicdn.com/tfs/TB1GxwdSXXXXXa.aXXXXXXXXXXX-65-70.gif"></a>
                <a href="http://www.beian.gov.cn/portal/registerSystemInfo?spm=5176.12825654.7y9jhqsfz.112.e9392c4aykru4M&amp;aly_as=U0MWfk4s" target="_blank" data-spm-anchor-id="5176.12825654.7y9jhqsfz.112">
                    <img data-src="//img.alicdn.com/tfs/TB1..50QpXXXXX7XpXXXXXXXXXX-40-40.png" style="width: 20px;" src="//img.alicdn.com/tfs/TB1..50QpXXXXX7XpXXXXXXXXXX-40-40.png">
                    <span>鲁ICP备19048465号</span>
                </a>
            </div>

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
    $.getJSON('<?php echo Yii::$app->params["API_URL"]?>/mall/v1/affiliate-plan/index?code=AF-1F&wx_xcx='+wx_xcx+'&callback=?&'+source, function(result){
    if(!result.status){
        return;
    }
    var html= template(swiper_content_tpl, {list:result.data,from:0,to:(result.data.length-1)});
    if(result.data.length>0){
        $("#swiper_content").show();
    }
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


//首页关闭缓存
    // if($.cookie('home_scroll')){
    // $(".content").scrollTop($.cookie('home_scroll'));
    // }
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
$this->registerJsFile('https://g.alicdn.com/opensearch/opensearch-console/0.16.0/scripts/jquery-ui-1.10.2.js',['depends'=>['fx\assets\AppAsset'],'position' => \yii\web\View::POS_END]);
$this->registerCssFile('https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css',['depends'=>['fx\assets\AppAsset'],'position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('/assets/script/auto_product_name.js',['depends'=>['fx\assets\AppAsset'],'position' => \yii\web\View::POS_END]);
?>
<?= fx\widgets\MainMenu::widget(); ?>
<?//=\h5\widgets\Block\Share::widget();?>
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
        'image' => Yii::$app->request->getHostInfo().'/assets/images/mrhuigou_logo.png'
    ];
}
?>
<?=\fx\widgets\Tools\Share::widget([
	'data'=>$data
])?>

