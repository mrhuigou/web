<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
?>

<?php if(count($products)){ ?>
    <?php foreach($products as $value){?>
        <?php if($value){ //库存大于0?>
            <div class="flex-col mb5 br5 whitebg f12 bs coupon-product ml10 mr10" data-id="<?=$value->product_code?>" data-param="<?=$value->affiliate_plan_id?>">
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
                    <a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$value->product_code,'shop_code'=>$value->store_code,'affiliate_plan_id'=>$value->affiliate_plan_id])?>"><img src="<?=\common\component\image\Image::resize(($value->image_url?:$imagelist)?:'',100,100)?>" alt="商品图片" width="95" height="95"></a>

                </div>

                <div class="flex-item-8 pt10">
                    <a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$value->product_code,'shop_code'=>$value->store_code,'affiliate_plan_id'=>$value->affiliate_plan_id])?>" class="f14"><?=$value->product->description->name?></a>
                    <p class="row-one red f13 mt5"><?php echo $value->title?></p>
                    <div class="pt10">

                        <div class="num-wrap num2 fr pr10 mt2 numDynamic ">
                            <?php
                            if($value->getQty($value->affiliate_plan_id) > 0){?>
                                <span class="num-lower iconfont"  style=""></span>
                                <input type="text" value="<?= $value->getQty($value->affiliate_plan_id)?>" class="num-text" style="">
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

    <?php $this->beginBlock('JS_NEWS') ?>

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
    var affiliate_plan_id=_this.parents(".coupon-product").data('param');
    Gooddisplaywiget(key,qty,affiliate_plan_id);

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
    var affiliate_plan_id=_this.parents(".coupon-product").data('param');
    Gooddisplaywiget(key,qty,affiliate_plan_id);
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
    var affiliate_plan_id=_this.parents(".coupon-product").data('param');
    Gooddisplaywiget(key,qty,affiliate_plan_id);
    });

    function Gooddisplaywiget(product_code,qty,affiliate_plan_id) {
    console.log(qty);
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
    <?php $this->endBlock() ?>
    <?php
    $this->registerJs($this->blocks['JS_NEWS'],\yii\web\View::POS_READY);
    ?>


<?php }?>
