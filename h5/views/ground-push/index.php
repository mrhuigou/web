<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/4
 * Time: 15:54
 */
$this->title = "地推现场";

if($point_lists){
    foreach ($point_lists as $point_list){
        if($model->id == $point_list->id){
            $this->title = $point_list->name;
        }
    }
}
?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/site/index']) ?>">
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
        <?php $sourcefrom = Yii::$app->request->get('sourcefrom'); ?>
        <?php if($sourcefrom == 'baijin'){?>
            <a href="<?php echo \yii\helpers\Url::to(['coupon/coupon-rules','id'=>4])?>">
                <img src="http://img1.mrhuigou.com/group1/M00/06/E4/wKgB7l7m6HeAGtUjAAJYQ70gEzg897.jpg" class="w" />
            </a>
        <?php }else{?>
            <a href="<?php echo \yii\helpers\Url::to(['coupon/coupon-rules','id'=>4])?>">
                <img src="http://img1.mrhuigou.com/group1/M00/06/E4/wKgB7l7kcsaAf9lWAAIJi1q3Zbw603.jpg" class="w" />
            </a>
        <?php }?>

<!--        <a href="--><?php //echo \yii\helpers\Url::to(['coupon/coupon-rules','id'=>4])?><!--">-->
<!--        <img src="http://img1.mrhuigou.com/group1/M00/06/E4/wKgB7l7kcsaAf9lWAAIJi1q3Zbw603.jpg" class="w" />-->
<!--        </a>-->

        <?php
        if(time()>strtotime(date('Y-m-d 16:00:00',time()))){
            if(time()<strtotime(date('Y-m-d 23:00:00',time()))){
                $deliver_time="预计明天".date('m月d日',time()+60*60*24)." 08:00-13:00 送达";
            }else{
                $deliver_time="预计次日(".date('m月d日',time()+60*60*24).") 13:00-18:00 送达";
            }
        }else{
            if(time()<strtotime(date('Y-m-d 11:30:00',time()))){
                $deliver_time="预计今天(".date('m月d日',time()).") 13:00-18:00 送达";
            }else{
                $deliver_time="预计今晚(".date('m月d日',time()).") 18:00-22:00 送达";
            }
        }
        ?>
        <p class="p5 tc red f12">现在线上下单，<?php echo $deliver_time?></p>

    </div>
    <div class="bg-wh mb5 pb5">

        <div class="p10 pb5">
            <!-- 地址选择 -->
            <div class="ditui-sele">
                <i class="iconfont icon-l">&#xe65f;</i>

                <div class="dropdown" >
                    <?php if($point_lists){?>

                    <select name="select_option" id="select_option" class="input-m w" style=" border: 0px;">
                        <?php foreach ($point_lists as $point_list){?>
                            <?php if($model->id == $point_list->id){?>
                                <option value="<?php echo $point_list->code?>" readonly="readonly" <?php if($model->id == $point_list->id){ echo "selected='selected'";}?>><?php echo $point_list->name?></option>
                            <?php }?>
                            <?php }?>

                        </select>
                    <?php }?>
                </div>

            </div>

            <p class="mt10 mb2">地推商品仅限现场自提（ 不提供配送服务）</p>

        </div>

        <img src="../assets/images/ditui/ditui1.jpg" class="w" />

    </div>
    <?php if ($info) { ?>

        <div id="cart-list">
<!--            <p class="mt5 bg-wh pt10 pb10 pl10">自提点地址：<i class="red">--><?php //echo $model->address?><!--</i></p>-->
            <p class=" mb5 bg-wh pt5 pb5 pl10">最晚提货时间：<i class="red"><?php echo '当日 '.$info->shipping_end_time?></i></p>

            <?php foreach ($products as $key=>$value) { ?>
                <div class="flex-col flex-center store-item bdb  whitebg pr" data-content="<?=$value->product_code?>">
                    <?php if(!$value->stock || ($value->stock && $value->stock->quantity <=0)){?>
                        <i class="item-tip iconfont">&#xe696;</i>
                    <?php }?>

                    <label class="label-checkbox item-content flex-item-1 flex-row flex-middle flex-center tc"
                           style="line-height:79px;">
                        <?php if(!$value->stock || ($value->stock && $value->stock->quantity <=0)){?>
                            <input type="checkbox" value="<?=$value->product_code?>" name="item"  class="item" disabled id="<?=$key?>">
                        <?php }else{?>
                            <?php if(empty($cart)){?>
                                <input type="checkbox" value="<?=$value->product_code?>" name="item"  class="item" id="<?=$key?>">
                            <?php }else{ ?>
                                <?php if(isset($cart[$value->product_code]) && $cart[$value->product_code] >0){ //购物车内有该商品?>
                                    <input type="checkbox" value="<?=$value->product_code?>" name="item" checked class="item" id="<?=$key?>">
                                <?php }else{?>
                                    <input type="checkbox" value="<?=$value->product_code?>" name="item" class="item" id="<?=$key?>">
                                <?php }?>
                            <?php }?>


                        <?php }?>

                        <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                    </label>
                    <div class="flex-item-2 flex-row flex-middle flex-center p5 item-img img_click">
                        <a href="#">
                            <img src="<?=\common\component\image\Image::resize($value->product->image,100,100)?>"
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
                                <?php if(!$value->stock || ($value->stock && $value->stock->quantity <=0)){?>
                                    <p class="redbg white stock_status tc p5" style="display: block">库存不足</p>
                                <?php }?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php foreach ($product_outofstock as $key=>$value) { ?>
                <div class="flex-col flex-center store-item bdb  whitebg pr" data-content="<?=$value->product_code?>">
                    <?php if(!$value->stock || ($value->stock && $value->stock->quantity <=0)){?>
                        <i class="item-tip iconfont">&#xe696;</i>
                    <?php }?>


                    <div class="flex-item-2 flex-row flex-middle flex-center p5 item-img">
                        <a href="#">
                            <img src="<?=\common\component\image\Image::resize($value->product->image,100,100)?>"
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
    var obj=$(this).parents(".store-item");
    $.showLoading("正在加载");
    $.post('/ground-push/update-item',{id:'<?=$model->id?>',item:item,'qty':qty},function(data){
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
    var obj=$(this).parents(".store-item");
    $.showLoading("正在加载");
    $.post('/ground-push/update-item',{id:'<?=$model->id?>',item:item,'qty':qty},function(data){
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

        $.post('/ground-push/submit',{data:data_string,ground_push_plan_id:<?php echo $info? $info->id :0;?>},function(result){
            //location.href='ground-push/checkout';

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
    window.location.href = "<?php echo \yii\helpers\Url::to(['ground-push/index'])?>" + "?push_code="+ point_code;
});

<?php $this->endBlock() ?>
</script>
<?php
//\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
