<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/11/5
 * Time: 13:48
 */
use yii\helpers\Html;
$this->title ='优惠券详情';
?>
<style>
    .activity-1-coupon::after, .activity-1-coupon::before {
        background-color: #f4f4f4;
    }
</style>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport">
	<div class=" pb50 mb20">
        <div class="br5 bg-wh mb10 activity-1-coupon m10">
            <div class="flex-col bd-d-b">
                <div class="flex-item-7">
                    <h3 class="red pt5 mt1"><?=$model->name?></h3>
                    <span class="gray9"><?=$model->comment?$model->comment:$model->description?></span>
					<?php if($model->max_discount){?>
                        <span class="red">最高抵用￥<?=$model->max_discount?></span>
					<?php } ?>
                </div>
                <div class="flex-item-5 red tr">
	                <?php if($model->model!=='BUY_GIFTS'){?>
		                <?php if($model->type=='F'){?>
                            <span class="f25">￥</span><span class="f40"><?=$model->getRealDiscount()?></span>
		                <?php }else{?>
                            <span class="f40"><?=$model->getRealDiscount()?></span><span class="f25">折</span>
		                <?php } ?>
	                <?php }?>
                </div>
            </div>
	        <?php if($customer_coupon){?>
            <div class="f14 pt5">
            <span class="gray9">截止：<?=date('m-d',strtotime($customer_coupon->start_time))?>~<?=date('m-d H:i',strtotime($customer_coupon->end_time))?></span>
            </div>
	        <?php } ?>
        </div>
		<?php if($coupon_product){ ?>
			<?php foreach($coupon_product as $value){?>
                <?php if($value->product->stockCount >0){ ?>
		<div class="flex-col mb5 br5 whitebg f12 bs coupon-product ml10 mr10" data-id="<?=$value->product->product_id?>" data-param="<?=$value->product->getPrice()?>">
			<div class="flex-item-4 tc pt5 pb5">
				<a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$value->product->product_code,'shop_code'=>$value->product->store_code])?>"><img src="<?=\common\component\image\Image::resize($value->product->image,100,100)?>" alt="商品图片" width="95" height="95"></a>

            </div>

			<div class="flex-item-8 pt10">
				<a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$value->product->product_code,'shop_code'=>$value->product->store_code])?>" class="f14"><?=$value->product->description->name?> <?php if($value->product->format){?>[<i class="format fb red"><?=$value->product->format?></i>]<?php }?></a>
                <p class="row-one red f13 mt5"><?php echo $value->product->description->meta_description?></p>
                <div class="pt10">

					<div class="num-wrap num2 fr pr10 mt2 numDynamic ">
						<span class="num-lower iconfont"  style="display:none;"></span>
						<input type="text" value="0" class="num-text" style="display:none;">
						<span class="num-add iconfont" style="display:none;"></span>
                        <div class="add-click"><i class="iconfont"></i></div>
					</div>
					<p>
						<span class="red f20 mr5 ">￥<?=$value->product->getPrice()?></span>
						<span class="gray9 del">￥<?=$value->product->price?></span>
					</p>
				</div>
			</div>
		</div>
                <?php } ?>
			<?php } ?>
		<?php } ?>
	</div>
</section>
<div class="pf-b  bs-t whitebg">
	<div id="coupon-product-list"></div>
	<div class="flex-col p10">
		<div class="flex-item-5 f12">
			<p id="coupon_msg" class="red fb"></p>
		</div>
		<div class="flex-item-3 tr pt2 pr5">
			<span class="f20 red" >￥<em id="sub_total">0.00</em></span>
		</div>
		<div class="flex-item-4 tc pt2">
			<a href="javascript:;" class="btn graybtn mbtn f12 disbtn" id="coupon_btn_submit">去结算</a>
		</div>
	</div>
</div>
<script type="text/html" id="goodlist_tpl">
	<div class=" w bdb  ellipsis "  style="overflow-x: auto;white-space: nowrap;">
		<% $.each(coupon_data,
		function(index, value)
		{ %>
		<img src="<%:=value.src%>" height="50" width="50" class="m5">
		<% }); %>
	</div>
</script>
<script>
<?php
$this->beginBlock('JS_SKU')
?>

$('.add-click').click(function(){
    $(this).hide();
    var wrap = $(this).parent(".num-wrap");
    wrap.find('.num-add').show();
    wrap.find('.num-lower').show();
    wrap.find('.num-add').click();
});
var coupon_data={};
/*数量*/
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
    var img_url=_this.parents(".coupon-product").find("img").attr('src');
    var price=_this.parents(".coupon-product").data('param');
    var qty=_this.parents(".coupon-product").find('.num-text').val();
    var total=FloatMul(price,qty);
//console.log("1111111=>"+qty);
    Gooddisplaywiget(key,qty,price,total,img_url,text);

    $.showLoading("正在提交");
});
function Gooddisplaywiget(key,qty,price,total,img_url,text){


    $.post("<?=\yii\helpers\Url::to(['/coupon/ajax-cart'],true)?>",{'data':[{'id':key,'qty':qty}]},function(data){
        $.hideLoading();
        if(!data.status){
            $.alert(data.message);
            qty = data.max_buy_quantity.max_quantity;

            text.val(qty);
            if(coupon_data[key]){
                if(qty==0){
                    delete coupon_data[key];
                }else{
                    coupon_data[key].qty=qty;
                    coupon_data[key].price=price;
                    coupon_data[key].total=total;
                }
            }else{
                coupon_data[key]={
                    id:key,
                    price:price,
                    qty:qty,
                    total:total,
                    src:img_url
                };
            }
            fillingHtml(coupon_data);
            return false;
        }else{
            //console.log("222222=>"+qty);
            if(coupon_data[key]){
               // console.log("333333=>"+qty);
                if(qty==0){
                    delete coupon_data[key];
                }else{
                    coupon_data[key].qty=qty;
                    coupon_data[key].price=price;
                    coupon_data[key].total=total;
                }
            }else{
                coupon_data[key]={
                    id:key,
                    price:price,
                    qty:qty,
                    total:total,
                    src:img_url
                };
            }
            console.log(coupon_data);
            fillingHtml(coupon_data);
        }
    },'json');



}
 function fillingHtml(carts) {
     console.log(carts);
     var sub_total=0;
     var sub_qty=0;
     var list_data=new Array();
     $.each(carts,
         function(index, value)
         {
             sub_total=FloatAdd(sub_total,value.total);
             sub_qty=FloatAdd(sub_qty,value.qty);
             list_data.push(value);
         }
     );
     console.log('subtotal=>'+sub_total);
    var tpl=$("#goodlist_tpl").html();
    var html_data= template(tpl, {list:list_data});
    if(list_data.length){
        $('#coupon-product-list').html(html_data);
        $(".veiwport").addClass("pb100");
    }else{
        $('#coupon-product-list').html("");
        $(".veiwport").removeClass("pb100");
    }
    var status=true;
    if(sub_total < <?=$model->total?>){
        status=false;
        $("#coupon_msg").html("还差￥"+FloatSub(<?=$model->total?>,sub_total)+'元可用');
    }
    if(sub_qty < <?=$model->limit_min_quantity?$model->limit_min_quantity:0?>){
        status=false;
        $("#coupon_msg").html("还差￥"+FloatSub(<?=$model->limit_min_quantity?>,sub_qty)+'个数量可用');
    }
    if(status){
        $("#coupon_btn_submit").removeClass('disbtn graybtn').addClass("greenbtn");
        $("#coupon_msg").html("");
    }else{
        $("#coupon_btn_submit").addClass('disbtn graybtn').removeClass("greenbtn");
    }
    $("#sub_total").html(sub_total);
    
}
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
    var img_url=_this.parents(".coupon-product").find("img").attr('src');
    var price=_this.parents(".coupon-product").data('param');
    var qty=_this.parents(".coupon-product").find('.num-text').val();
    var total=FloatMul(price,qty);
    Gooddisplaywiget(key,qty,price,total,img_url,text);
});
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
$("body").on('click','#coupon_btn_submit',function(){
    $.showLoading("正在加载");
    $.post("<?=\yii\helpers\Url::to(['/coupon/ajax-cart','is_push'=>1],true)?>",{'data':coupon_data},function(data){
        $.hideLoading();
        if(data.status){
            location.href=data.redirect;
        }else{
            $.alert(data.message);
        }
    },'json');
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_SKU'], \yii\web\View::POS_END);
?>
</script>
