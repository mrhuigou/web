<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/4
 * Time: 15:54
 */
$this->title = "一起团";
?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="/">
            <i class="aui-icon aui-icon-home green f28"></i>
        </a>
        <div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($this->title) ?>
        </div>
        <a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/user/index']) ?>">
            <i class="iconfont green f28">&#xe603;</i>
        </a>
    </div>
</header>
<div class="content  bc">
    <!-- 新改图片 -->
    <div class="bg-wh pb5 mb5">
        <a href="#">
        <img src="../assets/images/ditui/gp-top.jpeg" class="w" />
        </a>
    </div>
    <div class="bg-wh mb5 pb5">

        <div class="p10 pb5">
<!--            地址选择-->
            <div class="ditui-sele">
                <i class="iconfont icon-l">&#xe65f;</i>

                <div class="dropdown" >
                    <?php if($affiliate_info){?>
                        <select name="select_option" id="select_option" class="input-m w" style=" border: 0px;">
                            <option value="<?php echo $affiliate_info->code?>" readonly="readonly" selected ><?php echo $affiliate_info->username?></option>
                        </select>
                    <?php }?>
                </div>

            </div>
        </div>
    </div>


        <div id="swiper_content" style="max-width: inherit;width: 32rem;overflow: hidden;"></div>
        <script id="swiper_content_tpl" type="text/html">
            <div class="swiper-container" id="swiper-container_banner">
                <div class="swiper-wrapper">
                    <% for(var i=from; i<=to; i++) {%>
                    <div class="swiper-slide"  style="min-height: 2rem;">
                        <p class=" mb5 bg-wh pt5 pb5 pl10">方案名称：<i class="red"><%:=list[i].name%></i></p>
                        <p class=" mb5 bg-wh pt5 pb5 pl10">开团时间：<i class="red"><%:=list[i].date_start%></i></p>
                        <p class=" mb5 bg-wh pt5 pb5 pl10">闭团时间：<i class="red"><%:=list[i].date_end%></i></p>
                        <p class=" mb5 bg-wh pt5 pb5 pl10">配送时间：<i class="red"><%:=list[i].ship_end%></i></p>

                        <% if(list[i].products){%>
                            <div id="cart-list">
                                <% for(var ii=0; ii<=list[i].products.length-1; ii++) {%>
                                    <div class="flex-col flex-center store-item bdb  whitebg pr" data-content="<%:=list[i].products[ii].product_code%>" data-id="<%:=list[i].products[ii].affiliate_plan_detail_id%>">

                                        <label class="label-checkbox item-content flex-item-1 flex-row flex-middle flex-center tc"
                                               style="line-height:79px;">
                                            <% if(Object.keys(cart).length == 0){%>
                                                <input type="checkbox" value="<%:=list[i].products[ii].product_code%>" name="item"  class="item" id="<%ii%>">
                                            <% }else{ %>
                                                <% if((cart[list[i].products[ii].product_code]) && cart[list[i].products[ii].product_code] >0){ %>
                                                    <input type="checkbox" value="<%:=list[i].products[ii].product_code%>" name="item" checked class="item" id="<%ii%>">
                                                <% }else{ %>
                                                    <input type="checkbox" value="<%:=list[i].products[ii].product_code%>" name="item" class="item" id="<%ii%>">
                                                <% }%>
                                            <% }%>

                                            <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                        </label>

                                        <div class="flex-item-2 flex-row flex-middle flex-center p5 item-img img_click">
                                            <a href="#">
                                                <img src="<%:=list[i].products[ii].image_url%>"
                                                     class="bd w">
                                            </a>
                                        </div>
                                        <div class="flex-item-9 flex-row   p5">
                                            <div class="w">
                                                <h2 class="row-one"><%:=list[i].products[ii].product_name%></h2>
                                                <p class="gray9  mt2"><%:=list[i].products[ii].product_sku%></p>
                                            </div>
                                            <div class="flex-col w">
                                                <div class="red  fb lh200 f14 flex-item-4">
                                                    <p>￥<i class="product_total"><%:=list[i].products[ii].product_price%></i></p>
                                                </div>
                                                <div class="flex-item-8 flex-row  flex-center">
                                                    <p class="clearfix">
                                                        <span class="num-lower iconfont cart-num-lower"></span>

                                                        <input type="text" class="num-text cart-num-text" readonly value="<%:=list[i].products[ii].quantity%>"  max="<%:=list[i].products[ii].max_buy_qty%>" min="1">

                                                        <span class="num-add iconfont cart-num-add"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <% }%>
                            </div>
                        <% }else {%>
                            <div id="cart-list">暂无活动商品</div>
                        <% }%>




                        <p class=" mb5 bg-wh pt5 pb5 pl10"></p>
                    </div>


                    <% } %>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination swiper-pagination-red swiper-pagination-banner"></div>
            </div>
        </script>

    <?php if ($info) { ?>
        <div id="cart-list">

            <?php foreach ($products as $key=>$value) { ?>
                <div class="flex-col flex-center store-item bdb  whitebg pr" data-content="<?=$value->product_code?>" data-id="<?=$value->affiliate_plan_detail_id?>">
                    <label class="label-checkbox item-content flex-item-1 flex-row flex-middle flex-center tc"
                           style="line-height:79px;">
                            <?php if(empty($cart)){?>
                                <input type="checkbox" value="<?=$value->product_code?>" name="item"  class="item" id="<?=$key?>">
                            <?php }else{ ?>
                                <?php if(isset($cart[$value->product_code]) && $cart[$value->product_code] >0){ //购物车内有该商品?>
                                    <input type="checkbox" value="<?=$value->product_code?>" name="item" checked class="item" id="<?=$key?>">
                                <?php }else{?>
                                    <input type="checkbox" value="<?=$value->product_code?>" name="item" class="item" id="<?=$key?>">
                                <?php }?>
                            <?php }?>
                        <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                    </label>
                    <div class="flex-item-2 flex-row flex-middle flex-center p5 item-img img_click">
                        <a href="#">
                            <img src="<?=\common\component\image\Image::resize($value->image_url,100,100)?>"
                                 class="bd w">
                        </a>
                    </div>
                    <?php if(empty($cart)){
                        $quantity = 1;
                    }else{
                        if(isset($cart[$value->product_code]) && $cart[$value->product_code] >0){ //购物车内有该商品
                            $quantity = $cart[$value->product_code];
                        }else{
                            $quantity = 1;
                        }
                    }?>
                    <div class="flex-item-9 flex-row   p5">
                        <div class="w">
                            <h2 class="row-one"><?=$value->product->description->name?></h2>
                            <p class="gray9  mt2"><?=$value->product->getSku()?></p>
                        </div>
                        <div class="flex-col w">
                            <div class="red  fb lh200 f14 flex-item-4">
                                <p>￥<i class="product_total"><?=round(bcmul($value->price,$quantity,4),2)?></i></p>
                            </div>
                            <div class="flex-item-8 flex-row  flex-center">
                                <p class="clearfix">
                                    <span class="num-lower iconfont cart-num-lower"></span>

                                    <input type="text" class="num-text cart-num-text" readonly value="<?php echo $quantity?>"  max="<?=$value->max_buy_qty?>" min="1">

                                    <span class="num-add iconfont cart-num-add"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php }else{ ?>
        <div id="cart-list">暂无活动商品</div>
    <?php } ?>


</div>
<div class="fx-bottom flex-col bdt whitebg ">
    <label class="label-checkbox item-content flex-item-3 flex-row flex-middle flex-center graybg tc "
           style="line-height: 58px;">
        <input type="checkbox" name="selectAll"  id="selectAll">
        <div class="item-media "><i class="icon icon-form-checkbox vm"></i><span class="vm p2">全选</span></div>
    </label>
    <div class="flex-item-6 flex-row flex-middle flex-right  p10  ">
        <p>合计：<span class="red">￥<i id="cart_total">0.00</i></span></p>
        <p class="gray9">(不含运费)</p>
    </div>
    <a id="checkoutBtn" class="flex-item-3 flex-row flex-middle flex-center  tc p10 white   greenbg" href="javascript:;"
       style="line-height: 40px;">
        提交订单
    </a>
</div>
<script>
<?php $this->beginBlock('JS_END') ?>
resetTotal();
var select_cart_all=false;
// $(".item").change(function(){
$("body").bind('click','.item', function () {
    if($(".item:checked").length==$(".item").length){
        select_cart_all=true;
        $("#selectAll").attr("checked", true);
    }else{
        select_cart_all=false;
        $("#selectAll").attr("checked", false);
    }
    resetTotal();
});
$("body").on('click','.img_click', function () {
    var _this = $(this);
    _this.parent(".store-item").find(".item").trigger('click');
});
$("#selectAll").click(function () {//全选
    if(select_cart_all){
        select_cart_all=false;
        $("#cart-list :checkbox").each(function () {
            $(this).attr("checked", false);
        });
    }else{
        $("#cart-list :checkbox").each(function () {
            $(this).attr("checked", true);
        });
        select_cart_all=true;
    }
    resetTotal();
});
function resetTotal(){
    var car_total=0;
    $(".item").each(function(){
        if($(this).is(":checked")){
            var pt=$(this).parents(".store-item").find(".product_total").text();
            //var qty = $(this).parents(".store-item").find(".cart-num-text").val();
            //alert(qty);
            //var product_total = FloatMul(pt,qty);
            car_total=FloatAdd(pt,car_total);
        }
    });
    // $("#cart_total").html(car_total.toFixed(2));
    $("#cart_total").html(car_total);
}


$("body").on('click','.cart-num-add', function () {
    var num_obj=$(this).siblings(".cart-num-text");
    var max=num_obj.attr('max');
    var min=num_obj.attr('min');
    var qty=parseInt(num_obj.val());
    if(!max){
        max=100;
    }
    if(!min){
        min=1;
    }
    qty++;
    if(qty < min ){
        qty=parseInt(min);
    }
    if(qty <= parseInt(max) && qty >= parseInt(min)){
        num_obj.val(qty);
    }else{
        $.alert('最大限购'+parseInt(max)+'个');
        return;
    }
    $(this).parents('.store-item').find(".qty").text(qty);
    var item=$(this).parents('.store-item').attr("data-content");
    var item_id=$(this).parents('.store-item').attr("data-id");
    var obj=$(this).parents(".store-item");
    $.showLoading("正在加载");
    $.post('/affiliate-plan/update-item',{id:item_id,item:item,'qty':qty},function(data){
        $.hideLoading();
        if(data.status){
            obj.find(".cart-num-text").val(data.qty);
            obj.find(".product_total").html(data.sub_total);
            resetTotal();
        }else{
            qty = qty -1 ;
            obj.find(".cart-num-text").val(qty);
            $.alert(data.message);
        }
    },'json');
});


$("body").on('click','.cart-num-lower', function () {
    var num_obj=$(this).siblings(".cart-num-text");
    var max=num_obj.attr('max');
    var min=num_obj.attr('min');
    var qty=parseInt(num_obj.val());
    if(!max){
        max=100;
    }
    if(!min){
        min=1;
    }
    qty--;
    if(qty < min ){
        qty=parseInt(min);
    }
    if(qty <= parseInt(max) && qty >= parseInt(min)){
        num_obj.val(qty);
    }else{
        $.alert('最大限购'+parseInt(max)+'个');
    }
    $(this).parents('.store-item').find(".qty").text(qty);
    var item=$(this).parents('.store-item').attr("data-content");
    var item_id=$(this).parents('.store-item').attr("data-id");
    var obj=$(this).parents(".store-item");
    $.showLoading("正在加载");
    $.post('/affiliate-plan/update-item',{id:item_id,item:item,'qty':qty},function(data){
        $.hideLoading();
        if(data.status){
            obj.find(".cart-num-text").val(data.qty);
            obj.find(".product_total").html(data.sub_total);
            resetTotal();
        }else{
//            alert(qty);
//            qty = qty + 1 ;
//            obj.find(".cart-num-text").val(qty);
            $.alert(data.message);
        }
    },'json');
});
$("#checkoutBtn").click(function(){
    var data=[];
    var data_string = "";
    $(".item").each(function () {
        var sing_data = [];
        if($(this).attr("checked")){
           data.push($(this).val());
            var content = $(this).parents(".store-item");
            var product_code = $(this).val();
            var qty = parseInt(content.find(".cart-num-text").val());

            data_string = data_string + product_code + ',' + qty + ';';
        }
    });

    if(data.length>0){
        $.showLoading("正在提交");
        $('#form-checkout').submit();
        $.post('/affiliate-plan/submit',{data:data_string,affiliate_plan_id:<?php echo $info? $info->affiliate_plan_id :0;?>},function(result){
            //location.href='affiliate-plan/checkout';

            if(result && !result.status){
                $.hideLoading();
                $.alert(result.message);
            }
        },'json');
    }else{
        $.alert('请选择你要购买的商品');
    }
});

$(".ditui-sele .dropdown").dropdown('toggle');

$("#select_option").change(function(){
    var point_code = $(this).val();
    window.location.href = "<?php echo \yii\helpers\Url::to(['affiliate-plan/index'])?>" + "?plan_code="+ point_code;
});

var wx_xcx = <?php echo Yii::$app->session->get('source_from_agent_wx_xcx') ? 1:0  ?>;
var host = document.domain;
if(host.indexOf('mwx.') >=0){
    wx_xcx = 1;
}else{
    wx_xcx = 0;
}
var source = getSourceParms();


var swiper_content_tpl = $('#swiper_content_tpl').html();
// var source = getSourceParms();
//$.getJSON('<?php //echo Yii::$app->params["API_URL"]?>///mall/v1/ad/index?code=H5-0F-SLIDE&wx_xcx='+wx_xcx+'&callback=?&'+source, function(result){
    $.getJSON('/affiliate-plan/plan-info?type_code=DEFAULT&wx_xcx='+wx_xcx+'&callback=?&'+source, function(result){
    if(!result.status){
        return;
    }

    var html= template(swiper_content_tpl, {list:result.data,from:0,to:(result.data.length-1),cart:result.cart});

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

<?php $this->endBlock() ?>
</script>
<?php
//\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
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
<?=\affiliate\widgets\Tools\Share::widget([
    'data'=>$data
])?>