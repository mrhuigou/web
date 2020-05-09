<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='购物车';
?>
<header class=" header ">
    <div class="flex-col tc">
        <a class="flex-item-2" href="javascript:history.back();">
            <i class="aui-icon aui-icon-left green f28"></i>
        </a>
        <div class="flex-item-8 f16">
            <?=Html::encode($this->title) ?>
        </div>
        <div class="flex-item-2">
            <a class="red f16 cp action-edit" href="javascript:;">编辑</a>
        </div>
    </div>
</header>
<div class="content bdt">
<section class="veiwport pt5 mb50">
    <div id="cart-list">
    <?php foreach ( $cart as $val){ ?>
<!--        <div class="store_content mb10">-->
        <div class="store_content mb5">
        <h2 class="p10 db bdb whitebg clearfix ">
            <a class="fl " href="<?=\yii\helpers\Url::to(['/shop/index','shop_code'=>$val['base']->store_code],true)?>">
                店铺：<span class="gray6 mr5"><?=$val['base']['name']?></span>
                <span class="red"><?php if ($val['base']->befreepostage == 1) { //是否包邮（满X元包邮）
                        if ($val['base']->minbookcash > 0) {
                            echo '满' . $val['base']->minbookcash . '元包邮';
                        } else {
                            echo '包邮';
                        }
                    }
                    ?></span>
                <i class="iconfont gray6 fr " style="font-size: 12px;">&#xe60b;</i>
            </a>
            </h2>
        <?php foreach( $val['products'] as $key=>$value) { ?>
            <div class="flex-col flex-center store-item bdb  whitebg" data-content="<?=$key?>">
                <label class="label-checkbox item-content flex-item-1 flex-row flex-middle flex-center tc" style="line-height:79px;">
                    <input type="checkbox" value="<?=$key?>" name="item" checked class="item" id="<?=$key?>">
                    <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                </label>
                <div class="flex-item-2 flex-row flex-middle flex-center p5 item-img" >
                    <a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$value->product->product_code,'shop_code'=>$value->product->store_code,'affiliate_plan_id'=>$value->affiliate_plan_id])?>">
                    <img src="<?=\common\component\image\Image::resize($value->product->image?:current($value->product->productBase->imagelist),100,100)?>"  class="bd w">
                    </a>
                </div>
                <div class="flex-item-9 flex-row   p5" >
                    <div class="w">
                        <h2 class="row-one"><?=$value->product->description->name?></h2>
                        <p class="gray9  mt2"><?=$value->product->getSku()?></p>
                    </div>
                    <div class="flex-col w">
                        <div class="red  fb lh200 f14 flex-item-4">
                            <p>￥<i class="product_total"><?=$value->getCost() ?></i></p>
                        </div>
                        <div class="flex-item-8 flex-row  flex-center">
                            <p class="clearfix">
                                <span class="num-lower iconfont cart-num-lower"></span>
                                <input type="text" class="num-text cart-num-text" value="<?=$value->getQuantity()?>" max="100",min="1">
                                <span class="num-add iconfont cart-num-add"></span>
                            </p>
                            <p class="redbg white stock_status tc p5" style="display: <?=!$value->hasStock()?"":"none"?>">

	                            <?php if (!$value->hasStock()) { ?>

		                            <?php if ($max_sale = $value->product->getLimitMaxQty(\Yii::$app->user->getId())) {
			                            if ($value->product->getStockCount($value->getQuantity())) {
				                            if ($value->product->getStockCount($value->getQuantity()) < $value->getQuantity()) {
					                            echo '最多购买' . $value->product->getStockCount() . '件';
				                            }else{
					                            echo '最大限购' . $max_sale . '件';
                                            }
			                            } else {
				                            echo '库存不足';
			                            }
                                    } else {
                                        $stock_quantity = $value->product->getStockCount($value->getQuantity());
			                            if (!$stock_quantity) {
				                            echo '库存不足';
			                            }else{
                                            if ($stock_quantity  < $value->getQuantity()) {
                                                echo '最多购买' . $stock_quantity . '件';
                                            }
                                        }
		                            }
	                            }else{
                                    echo '库存不足';
                                }?>
                            </p>
                        </div>
                    </div>
                    <div class="promotion">
<!--                        --><?//=fx\widgets\Checkout\Promotion::widget(['promotion'=>$value->getPromotion(),'qty'=>$value->getQuantity()])?>
                    </div>
                </div>
                <a class="flex-item-2 flex-row flex-middle flex-center item-del del_item redbg tc" style="line-height:79px;display: none;" href="javascript:;">
                    <p class="white ">删除</p>
                </a>
            </div>
        <?php } ?>
        </div>
    <?php } ?>
    </div>
    <?=\fx\widgets\Cart\Relation::widget()?>
</section>
</div>
    <div class="fx-bottom flex-col bdt whitebg ">
        <label class="label-checkbox item-content flex-item-3 flex-row flex-middle flex-center graybg tc " style="line-height: 58px;">
            <input type="checkbox" name="selectAll" checked id="selectAll">
            <div class="item-media "><i class="icon icon-form-checkbox vm"></i><span class="vm p2">全选</span></div>
        </label>
        <div class="flex-item-6 flex-row flex-middle flex-right  p10  ">
            <p>合计：<span class="red">￥<i id="cart_total"><?=Yii::$app->fxcart->getCost(true)?></i></span></p>
            <p class="gray9">(不含运费)</p>
        </div>
        <a id="checkoutBtn" class="flex-item-3 flex-row flex-middle flex-center  tc p10 white   greenbg" href="javascript:;" style="line-height: 40px;">
            提交订单
        </a>
        <a id="selectDel" class="flex-item-3 flex-row flex-middle flex-center tc  p10 white   redbg" href="javascript:;" style="line-height: 40px; display: none; " >
            删除
        </a>
    </div>
<?php $this->beginBlock('JS_END') ?>
$(".action-edit").on("click",function(){
if($(this).text()=='编辑'){
    $(this).text('完成');
    $(".item-img").hide();
    $(".item-del").show();
    $("#checkoutBtn").hide();
    $("#selectDel").show();
}else{
    $(this).text('编辑');
    $(".item-img").show();
    $(".item-del").hide();
    $("#checkoutBtn").show();
    $("#selectDel").hide();
   if($(".item").length=0){
    location.reload(true);
    }
}
});

$("#checkoutBtn").click(function(){
    var data=[];
    $(".item").each(function () {
         if($(this).attr("checked")){
            data.push($(this).val());
        }
    });
    if(data.length>0){
    $.showLoading("正在提交");
    $('#form-checkout').submit();
         $.post('<?php echo \yii\helpers\Url::to(["/cart/submit"])?>',{data:data},function(res){
            if(res.status){
                location.href='<?php echo \yii\helpers\Url::to(["/checkout/index"])?>';
            }else{
                $.hideLoading();
                $.alert(res.message);
            }
         },'json');
    }else{
        $.alert('请选择你要购买的商品');
    }
});
$("#selectDel").click(function(){
    var data=[];
    $(".item").each(function () {
    if($(this).attr("checked")){
    data.push($(this).val());
    }
    });
    if(data.length>0){
    $.confirm("确定删除选择商品吗?",'友情提示',function(){
    $.showLoading("正在加载");
    $.post('<?php echo \yii\helpers\Url::to(["/cart/remove"])?>',{data:data},function(){
    $.hideLoading();
    location.reload(true);
    });
    });
    }else{
    $.alert('请选择你要删除的商品');
    }
});
    $(".del_item").click(function(){
        var obj=$(this);
        var data=[];
        data.push(obj.parents('.store-item').attr("data-content"));
        $.confirm("确定删除该商品吗?",'友情提示',function(){
            $.showLoading("正在加载");
            $.post('<?php echo \yii\helpers\Url::to(["/cart/remove"])?>',{data:data},function(){
                $.hideLoading();
                if(obj.parents('.store_content').find(".store-item").length>1){
                    obj.parents('.store-item').remove();
                    var car_total=0;
                    $(".item").each(function(){
                        if($(this).is(":checked")){
                            var pt=$(this).parents(".store-item").find(".product_total").html();
                            car_total=FloatAdd(pt,car_total);
                        }
                    });
<!--                    $("#cart_total").html(car_total.toFixed(2));-->
                    $("#cart_total").html(car_total);
                }else{
                    location.reload(true);
                }
            });
        });
    });
var select_cart_all=true;
$(".item").change(function(){
    if($(".item:checked").length==$(".item").length){
    select_cart_all=true;
    $("#selectAll").attr("checked", true);
    }else{
    select_cart_all=false;
    $("#selectAll").attr("checked", false);
    }
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
    var car_total=0;
    $(".item").each(function(){
    if($(this).is(":checked")){
    var pt=$(this).parents(".store-item").find(".product_total").text();
    car_total=FloatAdd(pt,car_total);
    }
    });
<!--    $("#cart_total").html(car_total.toFixed(2));-->
    $("#cart_total").html(car_total);
});
$(".item").on("click",function(){
    var car_total=0;
    $(".item").each(function(){
    if($(this).is(":checked")){
    var pt=$(this).parents(".store-item").find(".product_total").text();
    car_total=FloatAdd(pt,car_total);
    }
    });
<!--    $("#cart_total").html(car_total.toFixed(2));-->
    $("#cart_total").html(car_total);
    });
$(".cart-num-text").blur(function(){
    var num_obj=$(this);
    var max=num_obj.attr('max');
    var min=num_obj.attr('min');
    var qty=parseInt(num_obj.val());
    if(!max){
    max=100;
    }
    if(!min){
    min=1;
    }
    if(isNaN(qty) && qty < min){
    qty=min;
    }
    if(qty <= parseInt(max) && qty >= parseInt(min)){
    num_obj.val(qty);
    }else{
    qty=min;
    num_obj.val(min);
    }
    $(this).parents('.store-item').find(".qty").text(qty);
    var item=$(this).parents('.store-item').attr("data-content");
    var obj=$(this).parents(".store-item");
    $.showLoading("正在加载");
    $.post('<?php echo \yii\helpers\Url::to(["/cart/update"])?>',{item:item,'qty':qty},function(data){
    $.hideLoading();
    obj.find(".cart-num-text").val(data.qty);
    obj.find(".product_discount").html(data.discount);
    obj.find(".product_total").html(data.sub_total);
    obj.find(".promotion").html(data.promotion);
    var car_total=0;
    $(".item").each(function(){
    if($(this).is(":checked")){
    var pt=$(this).parents(".store-item").find(".product_total").text();
    car_total=FloatAdd(pt,car_total);
    }
    });
<!--    $("#cart_total").html(car_total.toFixed(2));-->
    $("#cart_total").html(car_total);
    if(data.stock_status){
    obj.find('.stock_status').html(data.stock_status).fadeIn();
    setTimeout(function() {
    obj.find('.stock_status').html(data.stock_status).fadeOut();
    }, 1000);
    }else{
    obj.find('.stock_status').hide();
    }
    },'json');
});
$(".cart-num-add").click(function(){
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
    if(isNaN(qty) && qty < min ){
    qty=min;
    }
    if(qty <= parseInt(max) && qty >= parseInt(min)){
    num_obj.val(qty);
    }else{
    return;
    }
    $(this).parents('.store-item').find(".qty").text(qty);
    var item=$(this).parents('.store-item').attr("data-content");
    var obj=$(this).parents(".store-item");
    $.showLoading("正在加载");
    $.post('<?php echo \yii\helpers\Url::to(["/cart/update"])?>',{item:item,'qty':qty},function(data){
    $.hideLoading();
    obj.find(".cart-num-text").val(data.qty);
    obj.find(".product_discount").html(data.discount);
    obj.find(".product_total").html(data.sub_total);
    obj.find(".promotion").html(data.promotion);
    var car_total=0;
    $(".item").each(function(){
    if($(this).is(":checked")){
    var pt=$(this).parents(".store-item").find(".product_total").text();
    car_total=FloatAdd(pt,car_total);
    }
    });
<!--    $("#cart_total").html(car_total.toFixed(2));-->
    $("#cart_total").html(car_total);
    if(data.stock_status){
    obj.find('.stock_status').html(data.stock_status).fadeIn();
    setTimeout(function() {
    obj.find('.stock_status').html(data.stock_status).fadeOut();
    }, 1000);
    }else{
    obj.find('.stock_status').hide();
    }
    },'json');
});
$(".cart-num-lower").click(function(){
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
    if(isNaN(qty) && qty < min ){
    qty=min;
    }
    if(qty <= parseInt(max) && qty >= parseInt(min)){
    num_obj.val(qty);
    }else{
    return;
    }
    $(this).parents('.store-item').find(".qty").text(qty);
    var item=$(this).parents('.store-item').attr("data-content");
    var obj=$(this).parents(".store-item");
    $.showLoading("正在加载");
    $.post('<?php echo \yii\helpers\Url::to(["/cart/update"])?>',{item:item,'qty':qty},function(data){
    $.hideLoading();
    obj.find(".cart-num-text").val(data.qty);
    obj.find(".product_discount").html(data.discount);
    obj.find(".product_total").html(data.sub_total);
    obj.find(".promotion").html(data.promotion);
    var car_total=0;
    $(".item").each(function(){
    if($(this).is(":checked")){
    var pt=$(this).parents(".store-item").find(".product_total").text();
    car_total=FloatAdd(pt,car_total);
    }
    });
<!--    $("#cart_total").html(car_total.toFixed(2));-->
    $("#cart_total").html(car_total);
    if(data.stock_status){
    obj.find('.stock_status').html(data.stock_status).fadeIn();
    setTimeout(function() {
    obj.find('.stock_status').html(data.stock_status).fadeOut();
    }, 1000);
    }else{
    obj.find('.stock_status').hide();
    }
    },'json');
});
 $("img.lazy").scrollLoading({container:$(".content")});
<?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>