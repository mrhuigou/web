<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/11/5
 * Time: 13:48
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
<!--        <div class="br5 bg-wh mb10 activity-1-coupon m10">-->
<!--            <div class="flex-col bd-d-b">-->
<!--                <div class="flex-item-7">-->
<!--                    <h3 class="red pt5 mt1">--><?//=$model->name?><!--</h3>-->
<!--                    <span class="gray9">--><?//=$model->comment?$model->comment:$model->description?><!--</span>-->
<!--					--><?php //if($model->max_discount){?>
<!--                        <span class="red">最高抵用￥--><?//=$model->max_discount?><!--</span>-->
<!--					--><?php //} ?>
<!--                </div>-->
<!--                <div class="flex-item-5 red tr">-->
<!--	                --><?php //if($model->model!=='BUY_GIFTS'){?>
<!--		                --><?php //if($model->type=='F'){?>
<!--                            <span class="f25">￥</span><span class="f40">--><?//=$model->getRealDiscount()?><!--</span>-->
<!--		                --><?php //}else{?>
<!--                            <span class="f40">--><?//=$model->getRealDiscount()?><!--</span><span class="f25">折</span>-->
<!--		                --><?php //} ?>
<!--	                --><?php //}?>
<!--                </div>-->
<!--            </div>-->
<!--	        --><?php //if($customer_coupon){?>
<!--            <div class="f14 pt5">-->
<!--            <span class="gray9">截止：--><?//=date('m-d',strtotime($customer_coupon->start_time))?><!--~--><?//=date('m-d H:i',strtotime($customer_coupon->end_time))?><!--</span>-->
<!--            </div>-->
<!--	        --><?php //} ?>
<!--        </div>-->
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

<?php if(!$model->in_range == 1){?>
    <!--		<p class="mb5 p5 lh130 whitebg bd-green"> 配送区域仅限：<span class="red">市南区、市北区、崂山区、李沧区、四方区、城阳区、黄岛区</span>。其它区域暂时尚未开通，敬请谅解。-->
    <!--		</p>-->
<?php } else { ?>
    <div class=" mb5 p5 lh130 whitebg bd-green"> 配送区域仅限：<span class="red">市南区、市北区、四方区、李沧区(部分)、崂山区(部分)、<span class="fb">黄岛(暂停配送)</span></span>。其它区域暂时尚未开通，敬请谅解。
        <span class="cp showM h unl green f12">查看详细</span>
        <p class="aboutAd dn">市区配送区域介绍： <span class="red">
                1、西至西镇沿海/
2、南至五四广场沿海一线/
3、东至滨海大道（松岭路）滨海大道北面至世园大道为界，边界外配送崂山路两侧往东至九水东路交接处为界，崂山区不进风景区；
4、重庆路往北至湘潭路为界
5、四流路往北至四流中路与四流北路交界
6、黑龙江路北至湘潭路为界</span>
            <br>黄岛区配送区域介绍：
            <span class="red fb">
                黄岛区域暂停配送
                <!----西至昆仑山路，北至淮河路；南至海边--></span>
        </p>

    </div>
<?php } ?>

<?php $action = \yii\helpers\Url::to([Yii::$app->request->getUrl()]);?>
<?php $form = ActiveForm::begin(['id' => 'form-address', 'action'=>  $action,'fieldConfig' => [
    'template' => '<li><div class="t">{label}：</div><div class="c">{input}</div></li>{error}',
    'inputOptions' => ["class" => 'w f14'],
    'errorOptions' => ['class' => 'red pl5']
],]); ?>
<?//= $form->field($model, 'lat', ['template' => '{input}'])->hiddenInput(['id' => 'address-lat'])->label(false) ?>
<?//= $form->field($model, 'lng', ['template' => '{input}'])->hiddenInput(['id' => 'address-lng'])->label(false) ?>
<?//= $form->field($model, 'province', ['template' => '{input}'])->hiddenInput(['id' => 'province'])->label(false) ?>
<?//= $form->field($model, 'city', ['template' => '{input}'])->hiddenInput(['id' => 'city'])->label(false) ?>
<?//= $form->field($model, 'postcode', ['template' => '{input}'])->hiddenInput(['value' => '266001']) ?>
<?//= $form->field($model, 'poiaddress', ['template' => '{input}'])->hiddenInput(['id' => 'poiaddress'])?>
<?//= $form->field($model, 'poiname',['template' => '{input}'])->hiddenInput(['id' => 'poiname']) ?>
<?//= $form->field($model, 'district',['template' => '{input}'])->hiddenInput(['id' => 'district'])?>
<??>
    <ul class="line-book mt5">
    <?php if(!$model->in_range == 1){?>
        <li>
            <div class="t">选择地区：</div>
            <div class="c">
                <div class="weui-cell__bd">
                    <?php $p = $model->province ? $model->province : '山东省';
                    $c = $model->city ? $model->city : '青岛市';
                    $d = $model->district ? $model->district : '市北区';
                    ?>
                    <input class="w f14" id="start" type="text"  value="<?php echo $p.' '.$c.' '.$d;?>">
                </div>
            </div>

        </li>
    <?php }else{?>
        <!--            <li><div class="t">所在城市：</div><div class="c">青岛市</div><div class="d">青岛市</div> </li>-->
        <li>
            <div class="t">选择地区：</div>
            <div class="c">
                <div class="weui-cell__bd">
                    <?php $p = $model->province ? $model->province : '山东省';
                    $c = $model->city ? $model->city : '青岛市';
                    $d = $model->district ? $model->district : '市北区';
                    ?>
                    <input class="w f14" id="start" type="text"  value="<?php echo $p.' '.$c.' '.$d;?>">
                </div>
            </div>
        </li>
        <p class="red pl5 error_district"></p>
    <?php }?>
    <?= $form->field($model, 'address',['template' => '{label}<li>{input}</li>{error}'])->textarea(["placeholder" => '小区/写字楼/街道+楼号+楼层等','id'=>'address','class'=>'w f14 ','rows'=>2,'style'=>"height:45px;padding:5px;"])->label('详细地址')?>
    <?= $form->field($model, 'username', ['inputOptions' => ["placeholder" => '请填写收货人姓名']])->label('收货人') ?>
    <?= $form->field($model, 'telephone', ['inputOptions' => ["placeholder" => '请填写收货人电话号码']])->label('手机号') ?>
    <?php if(!$model->in_range == 1){?> <??>
        <?= $form->field($model, 'is_default',['template' => "<div class='p5'>{input} {label}</div>",])->hiddenInput([
            'template' => "<div class='p5'>{input} {label}</div>",
            'value'=>0
        ])->label(false) ?>
    <?php }else{?>
        <?= $form->field($model, 'is_default')->checkbox(['template' => "<div class='p5'>{input} {label}</div>"])->label('默认地址') ?>
    <?php }?>
</ul>
<?php $store_id = 1;//家润?>
<?= h5\widgets\Checkout\Delivery::widget(['store_id' => $store_id]) ?>
<div class=" bdt  p10 w tc ">
    <a href="javascript:void(0)"  class='btn mbtn greenbtn w SubmitBtn'>提交订单 </a>
</div>
<?php ActiveForm::end(); ?>


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
<?php $this->beginBlock('JS_SKU')?>

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
    alert('提交订单成功 （测试）');
    //$.showLoading("正在加载");
    //$.post("<?//=\yii\helpers\Url::to(['/coupon/ajax-cart','is_push'=>1],true)?>//",{'data':coupon_data},function(data){
    //    $.hideLoading();
    //    if(data.status){
    //        location.href=data.redirect;
    //    }else{
    //        $.alert(data.message);
    //    }
    //},'json');
});

});
<?php $this->endBlock() ?>
</script>
<script>
<?php $this->beginBlock("JS_QQDiTu") ?>
//window.addEventListener('message', function(event) {
//    $("#map-contain").hide();
//    $.showIndicator();
//// 接收位置信息，用户选择确认位置点后选点组件会触发该事件，回传用户的位置信息
//    var loc = event.data;
//    console.log(loc);
//    if (loc && loc.module == 'locationPicker') {//防止其他应用也会向该页面post信息，需判断module是否为'locationPicker'
//        $.post('<?//=\yii\helpers\Url::to('/address/geo')?>//',{lat:loc.latlng.lat,lng:loc.latlng.lng},function(data){
//            if(data){
//                if(loc.poiname=="我的位置"){
//                    $("#poiname").val(data.title);
//                    $("#poiaddress").val(data.address);
//                    $("#address").val(data.address+data.title);
//                }else{
//                    $("#poiname").val(loc.poiname);
//                    $("#poiaddress").val(loc.poiaddress.replace(data.province+data.city,""));
//                    $("#address").val(loc.poiaddress.replace(data.province+data.city,"")+loc.poiname);
//                }
//                $("#province").val(data.province);
//                $("#city").val(data.city);
//                $("#district").val(data.district);
//                $("#address-lat").val(data.lat);
//                $("#address-lng").val(data.lng);
//            }
//        });
//        $.hideIndicator();
//    }
//}, false);
$("#pop-map").click(function(){
    $("#map-contain").show();
});
/*地址选择*/
$("#start").cityPicker({
    title: "选择地址",
    onChange: function (picker, values, displayValues) {
        $("#province").val(displayValues[0]);
        $("#city").val(displayValues[1]);
        $("#district").val(displayValues[2]);

    }
});

$("body").on("click",".showM.h",function () {
    $(this).removeClass("h").addClass("s").text("收起");
    $(".aboutAd").show();
})

$("body").on("click",".showM.s",function () {
    $(this).removeClass("s").addClass("h").text("查看详细");
    $(".aboutAd").hide();
});
$("body").on("click",".SubmitBtn",function () {
    if( !$("#district").val() ||  $("#district").val() == '请选择'){
        $('.error_district').html("请选择地区");
        $('.error_district').show();

    }

    $("#form-address").submit();
});



<?php $this->endBlock() ?>
</script>



<?php
$this->registerJs($this->blocks['JS_SKU'], \yii\web\View::POS_END);
?>
<?php
$this->registerJs($this->blocks['JS_QQDiTu'], \yii\web\View::POS_READY);
$this->registerJsFile("/assets/script/jqweui-picker.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/jqweui-city-picker.js",['depends'=>\h5\assets\AppAsset::className()]);
?>

