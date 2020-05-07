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
                <?php if (in_array($value['advertise_media_type'],['PACK','IMAGE'])) { ?>
                    <li class="clearfix mb5 pl5 pr5" style="overflow: hidden;">
                        <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
                            <img
                                    data-original="<?= \common\component\image\Image::resize($value->source_url) ?>"
                                    title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                    class="db w lazy" >
                        </a>
                    </li>
                <?php } ?>
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

    <!--分销方案商品列表-->
    <?php if(count($products)){ ?>
        <?php foreach($products as $value){?>
            <?php if($value){ //库存大于0?>
                <div class="flex-col mb5 br5 whitebg f12 bs coupon-product ml10 mr10" data-id="<?=$value->product_code?>" data-param="<?=$value->getPrice()?>">
                    <div class="flex-item-4 tc pt5 pb5">

                        <?php
                        //对商品图进行处理
                        $imagelist = '';
                        $images = $value->product->productBase->imagelist;
                        if($images){
                            foreach ($images as $value_image){
                                if(empty($imagelist)){
                                    $imagelist = $value_image;
                                }
                            }
                        }

                        ?>
                        <a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$value->product_code,'shop_code'=>$value->store_code])?>"><img src="<?=\common\component\image\Image::resize(($value->image_url?:$imagelist)?:'',100,100)?>" alt="商品图片" width="95" height="95"></a>

                    </div>

                    <div class="flex-item-8 pt10">
                        <a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$value->product_code,'shop_code'=>$value->store_code])?>" class="f14"><?=$value->product->description->name?></a>
                        <p class="row-one red f13 mt5"><?php echo $value->title?></p>
                        <div class="pt10">

                            <div class="num-wrap num2 fr pr10 mt2 numDynamic ">
                                <?php
                                if($value->getQty() > 0){?>
                                    <span class="num-lower iconfont"  style=""></span>
                                    <input type="text" value="<?= $value->getQty()?>" class="num-text" style="">
                                    <span class="num-add iconfont" style=""></span>
                               <?php }else{?>
                                    <span class="num-lower iconfont"  style="display:none;"></span>
                                    <input type="text" value="0" class="num-text" style="display:none;">
                                    <span class="num-add iconfont" style="display:none;"></span>
                                    <div class="add-click"><i class="iconfont"></i></div>
                               <?php }?>


                            </div>
                            <p>
                                <span class="red f20 mr5 ">￥<?= $value->getPrice()?></span>
                                <span class="gray9 del">￥<?=$value->product->price?></span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php }?>

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

$('.add-click').click(function(){
    $(this).hide();
    var wrap = $(this).parent(".num-wrap");
    wrap.find('.num-add').show();
    wrap.find('.num-lower').show();
    wrap.find('.num-add').click();
});

//基础代码
$(".num-add").click(function(){
    var _this = $(this);
    var wrap = _this.parent(".num-wrap");
    var text = wrap.find(".num-text");
    var lower = wrap.find(".num-lower");
    var a=text.val();
    a++;
    text.val(a);
    if(text.val() == 1){
        lower.addClass("first");
    }else{
        lower.removeClass("first");
    }
    var key=_this.parents(".coupon-product").data('id');
    var qty=_this.parents(".coupon-product").find('.num-text').val();
    Gooddisplaywiget(key,qty);

    // $.showLoading("正在提交");
});

$(".num-lower").click(function(){
    var _this = $(this);
    var wrap = _this.parent(".num-wrap");
    var text = wrap.find(".num-text");
    var lower = wrap.find(".num-lower");
    var a=text.val();
    if(a>0){
        a--;
    }
    text.val(a);
    if(text.val() == 1){
        lower.addClass("first");
    }else{
        lower.removeClass("first");
    }
    var key=_this.parents(".coupon-product").data('id');
    var qty=_this.parents(".coupon-product").find('.num-text').val();
    Gooddisplaywiget(key,qty);
});

$(".num-text").blur(function(){
    var _this = $(this);
    var wrap = _this.parent(".num-wrap");
    var text = wrap.find(".num-text");
    var lower = wrap.find(".num-lower");
    var a=text.val();
    text.val(a);
    if(text.val() == 1){
        lower.addClass("first");
    }else{
        lower.removeClass("first");
    }
    var key=_this.parents(".coupon-product").data('id');
    var qty=_this.parents(".coupon-product").find('.num-text').val();
    Gooddisplaywiget(key,qty);
});

function Gooddisplaywiget(product_code,qty) {
    console.log(qty);
    $.post('/cart/add-to-cart-fx', {
        'product_code': product_code,
        'qty': qty
    }, function (data) {
        $.hideIndicator();
        if(data.status){
            layer.open({
                content: '添加购物车成功',
                skin: 'msg',
                time: 2 //2秒后自动关闭
            });
        }else{
            layer.open({
                content:data.message,
                skin: 'msg',
                time: 2
            });
        }
    },'json');

    fillingHtml();
}

function fillingHtml() {

}
$(".numDynamic .num-add").click(function(){
    var text = $(this).siblings(".num-text");
    if(parseInt(text.val()) > 0){
        $(this).siblings(".num-lower").show();
        $(this).siblings(".num-text").show();
    }else{
        $(this).siblings(".num-lower").hide();
        $(this).siblings(".num-text").hide();
    }
})
$(".numDynamic .num-lower").click(function(){
    var text = $(this).siblings(".num-text");
    if(parseInt(text.val()) > 0){
        $(this).show();
        $(this).siblings(".num-text").show();
    }else{
        $(this).hide();
        $(this).siblings(".num-text").hide();
        $(this).siblings(".num-add").hide();
        $(this).siblings(".add-click").show();
    }
})


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

