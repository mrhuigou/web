<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/4
 * Time: 15:54
 */
use yii\helpers\Url;
$this->title = "每日惠购";
?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="#">
            <i class="aui-icon aui-icon-home white f28"></i>
        </a>
        <div class="flex-item-8 f16">
            <?= \yii\helpers\Html::encode($this->title) ?>
        </div>
        <a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/user/index']) ?>">
            <i class="iconfont green f28">&#xe603;</i>
        </a>
    </div>
</header>
<div class="content  bc" >
    <!--团长头像-->
    <div class="fx-head-img" style="padding: 0 0;">
        <div class="  flex-col flex-middle p10">
            <div class="flex-item-3" style="width: 0;">
                <a href="#" style=" line-height: 120px;">
                    <img src="<?= \common\component\image\Image::resize(!empty($affiliate_info)?$affiliate_info->customer->photo:'/assets/images/defaul.png', 100, 100) ?>"
                         alt="每日惠购" class="ava mava">
                </a>
            </div>
        </div>
    </div>
    <ul name="select_option" id="select_option" class="filter redFilter f16 clearfix" style="border-bottom: 1px solid #ff463c;">
        <?php if($affiliate_plans){?>
            <?php  foreach ($affiliate_plans as $affiliate_plan_value){?>
                <li class="<?=($affiliate_plan->affiliate_plan_id == $affiliate_plan_value->affiliate_plan_id) ?'cur':''?>" style="width: <?=100/count($affiliate_plans) .'%'?>">
                    <a href="#" class="select_option" data-content="<?= $affiliate_plan_value->code ?>"><?php echo $affiliate_plan_value->name?></a>

                </li>
            <?php }?>
        <?php }?>
    </ul>

    <?php if ($affiliate_plan) { ?>
        <div id="cart-list">
<!--            --><?php //if($affiliate_info && $affiliate_info->mode == 'DOWN_LINE'){?>
                <p class="mt5 bg-wh pt10 pb10 pl10"><i class="red"><?php echo $affiliate_info->username?></i></p>
<!--            --><?php //}?>
            <p class=" mb5 bg-wh pt5 pb5 pl10">
<!--                开团时间：<i class="red">--><?php //echo date('Y-m-d',strtotime($affiliate_plan->date_start))?><!--</i>-->

                结单时间：<i class="red"><?php echo date('Y-m-d',strtotime($affiliate_plan->date_end))?></i>
                &nbsp;&nbsp;&nbsp;
                配送时间：<i class="red"><?php echo date('Y-m-d',strtotime($affiliate_plan->ship_end))?></i>
            </p>
<!--            <p class=" mb5 bg-wh pt5 pb5 pl10">结单时间：<i class="red">--><?php //echo date('Y-m-d',strtotime($affiliate_plan->date_end))?><!--</i></p>-->
<!--            <p class=" mb5 bg-wh pt5 pb5 pl10">配送时间：<i class="red">--><?php //echo date('Y-m-d',strtotime($affiliate_plan->ship_end))?><!--</i></p>-->

            <div id="skill_content">
                <div class="tit1 clearfix">
                    <h2 class="fl red fb">限时抢购</h2>
                    <p class="fl countdown">
                        <?php
                        $days = 0;
                        if(strtotime($affiliate_plan->date_end) - time() > 0){
                            $days = floor((strtotime($affiliate_plan->date_end) - time()) / 86400);
                        }
                        ?>
                        <?php if($days > 0){?>
                            <span><?= $days?>天</span>
                        <?php }?>
                        <span class="hour_show">00</span>:<span class="minute_show">00</span>:<span class="second_show">00</span>
                    </p>
                </div>
            </div>

            <?php foreach ($products as $key=>$value) { ?>
                <div class="flex-col flex-center store-item bdb  whitebg pr" data-content="<?=$value->product_code?>" data-id="<?=$value->affiliate_plan_detail_id?>">
                    <label class="label-checkbox item-content flex-item-1 flex-row flex-middle flex-center tc"
                           style="line-height:79px;">
                            <?php if(empty($cart)){?>
                                <input type="checkbox" value="<?=$value->product_code?>" name="item"  class="item" id="<?=$key?>">
                            <?php }else{?>
                                <?php if(empty($cart[$affiliate_plan->affiliate_plan_id])){?>
                                    <input type="checkbox" value="<?=$value->product_code?>" name="item"  class="item" id="<?=$key?>">
                                <?php }else{ ?>
                                    <?php if(isset($cart[$affiliate_plan->affiliate_plan_id][$value->product_code]) && $cart[$affiliate_plan->affiliate_plan_id][$value->product_code] >0){ //购物车内有该商品?>
                                        <input type="checkbox" value="<?=$value->product_code?>" name="item" checked class="item" id="<?=$key?>">
                                    <?php }else{?>
                                        <input type="checkbox" value="<?=$value->product_code?>" name="item" class="item" id="<?=$key?>">
                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                    </label>
                    <div class="flex-item-4 flex-row flex-middle flex-center p5 item-img img_click">
                        <?php
                        //对展示图进行处理  没有图片时 使用包装图片
                        //对商品图进行处理
                        $imagelist = '';
                        $images = $value->product->productBase->imagelist;
                        if($images){
                            foreach ($images as $value1){
                                if(empty($imagelist)){
                                    $imagelist = $value1;
                                }
                            }
                        }

                        ?>
                        <a href="#">
                            <img src="<?=\common\component\image\Image::resize(($value->image_url?:$imagelist)?:'')?>"
                                 class="bd w">
                        </a>
                    </div>
                    <?php
                    if(empty($cart)){
                        $quantity = 1;
                    }else{
                        if(empty($cart[$affiliate_plan->affiliate_plan_id])){
                            $quantity = 1;
                        }else{
                            if(isset($cart[$affiliate_plan->affiliate_plan_id][$value->product_code]) && $cart[$affiliate_plan->affiliate_plan_id][$value->product_code] >0){ //购物车内有该商品
                                $quantity = $cart[$affiliate_plan->affiliate_plan_id][$value->product_code];
                            }else{
                                $quantity = 1;
                            }
                        }
                    }


                    ?>
                    <div class="flex-item-7 flex-row   p5">
                        <div class="w">
                            <h2 class=""><?=$value->product->description->name?></h2>
                            <p class="row-one red f13 mt5"><?php echo $value->title?></p>
                            <p class="gray9  mt2"><?=$value->product->getSku()?></p>
                        </div>
                        <div class="flex-col w">
                            <div class="red  fb lh200 f14 flex-item-4">
                                <p>￥<i class="product_total"><?=round(bcmul($value->price_type == 1 ? $value->price:$value->product->productBase->price ,$quantity,4),2)?></i></p>
                            </div>
                            <div class="flex-item-8 flex-row  flex-center">
                                <p class="clearfix">
                                    <span class="num-lower iconfont cart-num-lower"></span>

                                    <input type="text" class="num-text cart-num-text"  value="<?php echo $quantity?>"  max="<?=$value->max_buy_qty?>" min="1">

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
        <div class="item-media"><i class="icon icon-form-checkbox vm"></i><span class="vm p2">全选</span></div>
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
$(".item").change(function(){
    if($(".item:checked").length==$(".item").length){
            select_cart_all=true;
        $("#selectAll").attr("checked", true);
    }else{
        select_cart_all=false;
        $("#selectAll").attr("checked", false);
    }
    resetTotal();
});
$(".img_click").click(function () {
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
    // var car_total=0;
    var car_total=<?= $total;?>;
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

    if($(".item:checked").length > 0 && $(".item:checked").length==$(".item").length){
        $("#selectAll").attr("checked", true);
    }else{
        $("#selectAll").attr("checked", false);
    }
}

$(".cart-num-add").click(function () {
    var num_obj=$(this).siblings(".cart-num-text");
    var max=num_obj.attr('max');
    var min=num_obj.attr('min');
    var qty=parseInt(num_obj.val());
    if(!max || max == 0){
        max=10000;
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

$(".cart-num-lower").click(function(){
    var num_obj=$(this).siblings(".cart-num-text");
    var max=num_obj.attr('max');
    var min=num_obj.attr('min');
    var qty=parseInt(num_obj.val());
    if(!max || max == 0){
        max=10000;
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
$(".cart-num-text").blur(function(){
    var num_obj=$(this);
    var max=num_obj.attr('max');
    var min=num_obj.attr('min');
    var qty=parseInt(num_obj.val());
    if(!max || max == 0){
        max=10000;
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
        qty=max;
        num_obj.val(max);
    }
    $(this).parents('.store-item').find(".qty").text(qty);
    var item=$(this).parents('.store-item').attr("data-content");
    var item_id=$(this).parents('.store-item').attr("data-id");
    var obj=$(this).parents(".store-item");
    $.showLoading("正在加载");
    $.post('<?php echo \yii\helpers\Url::to(["/affiliate-plan/update-item"])?>',{id:item_id,item:item,'qty':qty},function(data){
        $.hideLoading();
        if(data.status){
            obj.find(".cart-num-text").val(data.qty);
            obj.find(".product_total").html(data.sub_total);
            resetTotal();
        }else{
            $.alert(data.message);
        }
    },'json');
});

$("#checkoutBtn").click(function(){
    var data=[];
    var data_string = "";
    var is_other_cart = "<?=$is_other_cart?>";
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

    if(data.length>0 || is_other_cart==1){
        $.showLoading("正在提交");
        $('#form-checkout').submit();
        $.post('/affiliate-plan/submit',{data:data_string,affiliate_plan_id:<?php echo $affiliate_plan? $affiliate_plan->affiliate_plan_id :0;?>},function(result){
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
    window.location.href = "<?php echo \yii\helpers\Url::to(['affiliate-plan/index'])?>" + "&plan_code="+ point_code;
});
$(".select_option").click(function(){
    var point_code = $(this).attr("data-content");
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
    $.showLoading("正在提交");
    // $('#form-checkout').submit();
    $.post('/affiliate-plan/switch-plan-submit',{data:data_string,affiliate_plan_id:<?php echo $affiliate_plan? $affiliate_plan->affiliate_plan_id :0;?>},function(result){
        if(result && !result.status){
            $.hideLoading();
            $.alert(result.message);
        }else {
            $.hideLoading();
            window.location.href = "<?php echo \yii\helpers\Url::to(['affiliate-plan/index'])?>" + "&plan_code="+ point_code;
        }

    },'json');



});
timer(<?= $plan_countdown?>,$("#skill_content"));

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
        'title' => $affiliate_plan?$affiliate_plan->name:"遇到好东西，总想分享给最亲爱的你。",
        'desc' => "每日惠购，物美价廉，当日订单，当日送达。",
        'link' => Yii::$app->request->getAbsoluteUrl(),
//        'image' => Yii::$app->request->getHostInfo().'/assets/images/mrhuigou_logo.png'
        'image' => 'https://m.mrhuigou.com/assets/images/mrhuigou_logo.png'
    ];
}
?>
<?=\fx\widgets\Tools\Share::widget([
    'data'=>$data
])?>