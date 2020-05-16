<?php
if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $this->title = "智慧生活";
}else{
	$this->title = "每日惠购-一起团";
}
?>

<div class="content" style="top: 0px;">
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
<!--    --><?php // echo \fx\widgets\Block\News::widget()?>
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
                        <a href="<?= \yii\helpers\Url::to(['affiliate-plan-detail/index','plan_id'=>$value->affiliate_plan_id]) ?>">
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
        <a class="fr f12 red mt2" href="<?=\yii\helpers\Url::to(['/affiliate-plan-detail/index','position'=>'AF-4F'])?>">更多&gt;&gt;</a>
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

    <ul class="filter redFilter two f16 clearfix" style="border-bottom: 1px solid #ff463c;">
        <li class="" id="jinri">
            <div id="jinri1">
                <img data-original="http://img1.mrhuigou.com/group1/M00/06/D8/wKgB7l6_peKAELAIAAAkILDuqBo736.jpg" title="正在抢购" alt="正在抢购" class="db w lazy" >
            </div>
            <div id="jinri2" style="display: none">
                <img data-original="http://img1.mrhuigou.com/group1/M00/06/D8/wKgB7l6_s9aALVCVAAANnjPagng521.jpg" title="正在抢购" alt="正在抢购" class="db w lazy">
            </div>
         </li>
        <li class="" id="mingri">
            <div id="mingri1">
                 <img data-original="http://img1.mrhuigou.com/group1/M00/06/D8/wKgB7l6_peKAJq1WAAAR4v-fXBc675.jpg" title="下期预购" alt="下期预购" class="db w lazy" >
            </div>
            <div id="mingri2" style="display: none">
                <img data-original="http://img1.mrhuigou.com/group1/M00/06/D8/wKgB7l6_tFqASLXwAAAUvihjChk406.jpg" title="下期预购" alt="下期预购" class="db w lazy" style="display: none" >
            </div>
         </li>
    </ul>
    <div id="affiliate_plan"></div>
    <script id="affiliate_plan_tpl" type="text/html">
        <% for(var i=from; i<=to; i++) {%>
        <div class="flex-col mb5 br5 whitebg f12 bs coupon-product ml10 mr10" data-id="<%:=list[i].pu_code%>" data-param="<%:=list[i].affiliate_plan_id%>">
            <div class="flex-item-4 tc pt5 pb5">
                <a href="<%:=list[i].url%>"><img src="<%:=list[i].image%>" alt="商品图片" width="95" height="95"></a>
            </div>

            <div class="flex-item-8 pt10">
                <a href="<%:=list[i].url%>" class="f14"><%:=list[i].name%></a>
                <p class="row-one red f13 mt5"><%:=list[i].title%></p>
                <div class="pt10">
                    <div class="num-wrap num2 fr pr10 mt2 numDynamic ">
                            <span class="num-lower iconfont"  style="display:<%:=list[i].count?'':'none'%>;"></span>
                            <input type="text" value="<%:=list[i].count%>" class="num-text" style="display:<%:=list[i].count?'':'none'%>;">
                            <span class="num-add iconfont" style="display:<%:=list[i].count?'':'none'%>;"></span>
                            <div class="add-click" style="display:<%:= list[i].plan_status == 1 ? 'none': ( list[i].count?'none':'')%>;"><i class="iconfont" ></i></div>
                    </div>
                    <p>
                        <span class="red f20 mr5 ">￥<%:=list[i].sale_price%></span>
                        <span class="gray9 del">￥<%:=list[i].vip_price%></span>
                    </p>
                </div>
            </div>
        </div>
        <% } %>
    </script>
    <!--分销方案商品列表-->
<!--    --><?php // echo \fx\widgets\Affiliate\AffiliatePlan::widget(['plan_type_code'=>'DEFAULT'])?>
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
    $.getJSON('<?php echo Yii::$app->params["API_URL"]?>/mall/v1/affiliate-plan/product?position=AF-4F&wx_xcx='+wx_xcx+'&callback=?&'+source, function(result){
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


var plan_status = 0;
var affiliate_plan_tpl = $('#affiliate_plan_tpl').html();
//$.getJSON('<?php //echo Yii::$app->params["API_URL"]?>///mall/v1/affiliate-plan/default-product?plan_status='+plan_status+'&wx_xcx='+wx_xcx+'&callback=?&'+source, function(result){
    $.post('<?php echo \yii\helpers\Url::to(["/affiliate-plan-detail/default-product"])?>',{plan_status:plan_status},function(result) {

        var html = template(affiliate_plan_tpl, {list:result.data,from:0,to:(result.data.length-1) });
    $("#affiliate_plan").html(html);
});

$("body").on('click','#jinri',function(){
    $('#jinri1').show();
    $('#mingri1').show();
    $('#jinri2').hide();
    $('#mingri2').hide();
    plan_status = 0;
    $.showLoading("正在加载");
    $.post('<?php echo \yii\helpers\Url::to(["/affiliate-plan-detail/default-product"])?>',{plan_status:plan_status},function(result) {
        $.hideLoading();
        var html= template(affiliate_plan_tpl, {list:result.data,from:0,to:(result.data.length-1) });
        $("#affiliate_plan").html(html);
    });
});
$("body").on('click','#mingri',function(){
    $('#jinri2').show();
    $('#mingri2').show();
    $('#jinri1').hide();
    $('#mingri1').hide();
    plan_status = 1;
    $.showLoading("正在加载");
    $.post('<?php echo \yii\helpers\Url::to(["/affiliate-plan-detail/default-product"])?>',{plan_status:plan_status},function(result) {
        $.hideLoading();
        var html= template(affiliate_plan_tpl, {list:result.data,from:0,to:(result.data.length-1) });
        $("#affiliate_plan").html(html);
    });
});

$("body").on('click','.add-click',function(){
    $(this).hide();
    var wrap = $(this).parent(".num-wrap");
    wrap.find('.num-add').show();
    wrap.find('.num-lower').show();
    wrap.find('.num-add').click();
});
//基础代码
$("body").on('click','.num-add',function(){
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
    var affiliate_plan_id=_this.parents(".coupon-product").data('param');
    $.post('/cart/add-to-cart-fx', {
        'product_code': key,
        'qty': qty,
        'affiliate_plan_id': affiliate_plan_id,
    }, function (data) {
        $.hideIndicator();
        if(data.status){
            layer.open({
                content: data.stock_status,
                skin: 'msg',
                time: 2 //2秒后自动关闭
            });
            $(".cart_qty").show().text(data.data);
            wrap.find(".num-text").val(data.qty);
        }else{
            layer.open({
                content:data.stock_status,
                skin: 'msg',
                time: 2
            });
            wrap.find(".num-text").val(data.qty);
            $(".cart_qty").show().text(data.data);
        }
    },'json');

});

$("body").on('click','.num-lower',function(){
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
    var affiliate_plan_id=_this.parents(".coupon-product").data('param');

    $.post('/cart/add-to-cart-fx', {
        'product_code': key,
        'qty': qty,
        'affiliate_plan_id': affiliate_plan_id,
    }, function (data) {
        $.hideIndicator();
        if(data.status){
            $(".cart_qty").show().text(data.data);
            wrap.find(".num-text").val(data.qty);
        }else{
            layer.open({
                content:data.stock_status,
                skin: 'msg',
                time: 2
            });
            wrap.find(".num-text").val(data.qty);
            $(".cart_qty").show().text(data.data);
        }
    },'json');
});


$("body").on('blur','.num-text',function(){
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
    var affiliate_plan_id=_this.parents(".coupon-product").data('param');
    // Gooddisplaywiget(key,qty,affiliate_plan_id,_this);

    $.post('/cart/update-fx', {
        'product_code': key,
        'qty': qty,
        'affiliate_plan_id': affiliate_plan_id,
    }, function (data) {
        $.hideIndicator();
        if(data.status){
            $(".cart_qty").show().text(data.data);
            wrap.find(".num-text").val(data.qty);
        }else{
            layer.open({
                content:data.stock_status,
                skin: 'msg',
                time: 2
            });
            wrap.find(".num-text").val(data.qty);
            $(".cart_qty").show().text(data.data);
        }
    },'json');
});


function Gooddisplaywiget(product_code,qty,affiliate_plan_id,_this='') {
    $.post('/cart/add-to-cart-fx', {
        'product_code': product_code,
        'qty': qty,
        'affiliate_plan_id': affiliate_plan_id,

    }, function (data) {
        $.hideIndicator();
        if(data.status){
            layer.open({
                content: '加入购物车成功',
                skin: 'msg',
                time: 2 //2秒后自动关闭
            });
            $(".cart_qty").text(data.data);
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

$("body").on('click','.numDynamic .num-add',function(){
    var text = $(this).siblings(".num-text");
    if(parseInt(text.val()) > 0){
        $(this).siblings(".num-lower").show();
        $(this).siblings(".num-text").show();
    }else{
        $(this).siblings(".num-lower").hide();
        $(this).siblings(".num-text").hide();
    }
})
$("body").on('click','.numDynamic .num-lower',function(){
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

