<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '购物车';
?>

    <div class="" style="min-width:1200px;">
        <div class="w1100 bc pt10">
            <section class="veiwport">

                <p class="shopcart_step"></p>

                <p class="mb10 mt10">
                    <span class="fr"><i class="org">温馨提示：</i>1.选购清单中的商品无法保留库存，请您及时结算。2.商品的价格和库存将以订单提交时为准。</span>
                    购物车状态：(<i class="red"><?= \Yii::$app->cart->getCount() ?></i>件)
                </p>

                <form action="" method="post" enctype="multipart/form-data" id="cartFormId">
                    <table cellpadding="0" cellspacing="0" class="shopcart_list w">
                        <thead class="graybg2">
                        <tr>
                            <td width="40%">商品名称</td>
                            <td width="12%">规格</td>
                            <td width="12%">单价</td>
                            <td width="14%">数量</td>
                            <td width="10%">总价</td>
                            <td width="12%">操作</td>
                        </tr>
                        </thead>
                    </table>

                    <?php foreach ($cart as $val) { ?>
                        <table cellpadding="0" cellspacing="0" class="shopcart_list mt5 w cart_store_products" id="store_<?= $val['base']['store_id'] ?>">
                            <thead>
                            <tr>
                                <th colspan="7" style="text-align: left;" class="graybg">
                                    <label>
                                    <input type="checkbox" class="storeSelect   vm" name="store_id"
                                           value="<?= $val['base']['store_id'] ?>" checked="checked"/>
                                    <span class=" ml5 vm"><?= $val['base']['name'] ?></span>
                                    <span class=" ml5 vm red"><?php if ($val['base']->befreepostage == 1) { //是否包邮（满X元包邮）
                                            if ($val['base']->minbookcash > 0) {
                                                echo '满' . $val['base']->minbookcash . '元包邮';
                                            } else {
                                                echo '包邮';
                                            }
                                        }
                                        ?></span>
                                    </label>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($val['products'] as $key => $value) { ?>
                                <tr id="<?php echo $key; ?>"  store_id="<?= $val['base']['store_id'] ?>" class="product_id">
                                    <td class="firstCheckbox">
                                        <input type="checkbox" class="groupSelect" name="item" value="<?php echo $key; ?>" checked="checked"/>
                                    </td>
                                    <td width="33%" class="clearfix" style="padding-left:20px; ">
                                        <div class="clearfix">
                                            <a href="<?= \yii\helpers\Url::to(['product/index', 'product_code' => $value->product->product_code, 'shop_code' => $value->product->store_code]) ?>" class="fl">
                                                <img  src="<?= \common\component\image\Image::resize($value->product->image, 45, 45) ?>"  width="45" height="45" class="bd mr10"  alt="<?php echo $value->product->description->name; ?>"  title="<?php echo $value->product->description->name; ?>"/>
                                            </a>
                                            <p class="fl mt5 clearfix tl pinfo w270">
                                                <a href="<?= \yii\helpers\Url::to(['/product/index', 'product_code' => $value->product->product_code, 'shop_code' => $value->product->store_code]) ?>"
                                                   class="mb5 dib"
                                                   title="<?php echo $value->product->description->name; ?>"><?php echo $value->product->description->name; ?></a>
                                                <br/>
                                                <span class="org"><?= $value->product->getSku()?></span>
                                            </p>
                                        </div>
                                        <div class="product_promotion">
                                            <?=frontend\widgets\Checkout\Promotion::widget(['promotion'=>$value->getPromotion(),'qty'=>$value->getQuantity()])?>
                                        </div>
                                    </td>
                                    <td width="14%">
                                        <i> <?= $value->product->format ?></i>
                                    </td>
                                    <td width="12%">
                                        <div style="line-height:18px;">
                                            ￥<i class="vip_price"><?= $value->getPrice() ?></i> <br/>
                                            <i class="del gray9">￥<?= $value->product->price ?></i> <br/>
                                            <i class="greenbg white pl5 pr5 ">立省:￥<em class="product-discount-price"><?php echo number_format(max(0,$value->product->price - $value->getPrice()), 2); ?></em></i>
                                        </div>
                                    </td>
                                    <td width="14%" class="cart_pt_add_low_td">
                                        <div class="add-lower clearfix cart_pt_add_low_num bc" style="width: 100px;">
                                            <input type="button" class="numLowerBtn num-lower" value="-"/>
                                            <input type="text" class="numTextBtn num-text" name="quantity[<?=$key?>]"  value="<?= $value->getQuantity() ?>">
                                            <input type="button" class="numAddBtn num-add" value="+"/>
                                        </div>
                                        <span class="stock_status" style="display: <?=$value->hasStock()?'none':''?>;">
                                           <?php if (!$value->hasStock()) { ?>
                                               <?php if($value->product->getStockCount()){ ?>
                                                   <?php if($value->product->getLimitMaxQty(\Yii::$app->user->getId())){?>
                                                       最多购买<?=min($value->product->getLimitMaxQty(\Yii::$app->user->getId()),$value->product->getStockCount())?>件
                                                   <?php }else{ ?>
                                                       最多购买<?=max(0,$value->product->getStockCount())?>件
                                                   <?php }?>
                                               <?php }else{?>
                                                   库存不足
                                               <?php } ?>
                                           <?php } ?>
                                        </span>
                                    </td>
                                    <td width="10%" class="org f14 ">
                                        ￥<span class="product-sub-total"><?php echo number_format($value->getCost(), 2); ?></span>
                                    </td>
                                    <td rowspan="" width="10%" class="lastDelete"><i class="cp del_item_btn">删除 </i></td>
                                </tr>
                            <?php } ?>


                            </tbody>
                        </table>
                    <?php } ?>
                    <table cellspacing="0" cellpadding="0" class="shopcart_list w mt5">
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td class="tl" colspan="2">
                                <label class="pr10 pl30"> <a class="shopcart_deleSele" href="javascript:void(0)">清空购物车</a></label>
                            </td>
                            <td class="tr" colspan="5">
                                已有<i id="selectedpcount" class="red"><?= count(Yii::$app->cart->getPositions()) ?></i>件商品
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                商品总额: <i id="sub_total"  class="org f18 bold pr20">￥<?= number_format(Yii::$app->cart->getCost(), 2) ?></i>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </form>
                <div class="clearfix mt10 pb10 tr">
                    <a href="/" class="btn btn_big greenBtn ">继续购物</a>
                    <a href="javascript:void(0)" id="checkoutBtn" class="btn btn_big orgBtn ">去结算</a>
                </div>
            </section>
        </div>
    </div>
<?php $this->beginBlock('JS_END') ?>
    $("#checkoutBtn").on("click",function(){
    var conform_data=[];
    $(":input[name='item']").each(function () {
    if($(this).attr("checked")){
    conform_data.push($(this).val());
    }
    });
    if(conform_data.length>0){
    $(this).text("正在提交...");
    $(this).off("click");
    $.post('/cart/submit',{data:conform_data},function(data){
    if(data.status){
    location.href='/checkout/index';
    }else{
    alert('数据异常');
   }
    },'json');
    }else{
    alert('请选择你要购买的商品');
    }
    });
    $(".num-text").on('blur',function(){
    var obj=$(this);
    var qty=obj.val();
    if(!IsNum(qty)){
    qty=1;
    }
    var item=obj.parents('tr').attr("id");
    $.post('/cart/update',{item:item,'qty':qty},function(data){
    obj.val(data.qty);
    obj.parents('tr').find('.vip_price').text(data.price);
    obj.parents('tr').find('.product-discount-price').text(data.discount);
    obj.parents('tr').find('.product-sub-total').text(data.sub_total);
    obj.parents('tr').find('.product_promotion').html(data.promotion);
    resetSelectProductTotal();
    if(data.stock_status){
    obj.parents('tr').find('.stock_status').html(data.stock_status).fadeIn();
    setTimeout(function() {
    obj.parents('tr').find('.stock_status').html(data.stock_status).fadeOut();
    }, 1000);
    }else{
    obj.parents('tr').find('.stock_status').hide();
    }
    },'json');
    });
    $(".num-add").on('click',function(){
    var obj=$(this);
    var qty=obj.siblings(".num-text").val();
    if(!IsNum(qty)){
    qty=1;
    }
    qty++;
    var item=obj.parents('tr').attr("id");
    $.post('/cart/update',{item:item,'qty':qty},function(data){
    obj.siblings(".num-text").val(data.qty);
    obj.parents('tr').find('.vip_price').text(data.price);
    obj.parents('tr').find('.product-discount-price').text(data.discount);
    obj.parents('tr').find('.product-sub-total').text(data.sub_total);
    obj.parents('tr').find('.product_promotion').html(data.promotion);
    resetSelectProductTotal();
    if(data.stock_status){
    obj.parents('tr').find('.stock_status').html(data.stock_status).fadeIn();
    setTimeout(function() {
    obj.parents('tr').find('.stock_status').html(data.stock_status).fadeOut();
    }, 1000);
    }else{
    obj.parents('tr').find('.stock_status').hide();
    }
    },'json');
    });
    $(".num-lower").on('click',function(){
    var obj=$(this);
    var qty=obj.siblings(".num-text").val();
    if(!IsNum(qty)){
    qty=1;
    }
    qty--;
    if(qty<1){
    qty=1;
    }
    var item=obj.parents('tr').attr("id");
    $.post('/cart/update',{item:item,'qty':qty},function(data){
    obj.siblings(".num-text").val(data.qty);
    obj.parents('tr').find('.vip_price').text(data.price);
    obj.parents('tr').find('.product-discount-price').text(data.discount);
    obj.parents('tr').find('.product-sub-total').text(data.sub_total);
    obj.parents('tr').find('.product_promotion').html(data.promotion);
    resetSelectProductTotal();
    if(data.stock_status){
    obj.parents('tr').find('.stock_status').html(data.stock_status).fadeIn();
    setTimeout(function() {
    obj.parents('tr').find('.stock_status').html(data.stock_status).fadeOut();
    }, 1000);
    }else{
    obj.parents('tr').find('.stock_status').hide();
    }
    },'json');
    });
   //购物车删除某组商品
    $(".del_item_btn").on('click',function(){
    var obj=$(this);
    if(confirm("确认删除该商品吗？")){
    var item = obj.parents('tr').attr("id");
    $.post('/cart/remove',{data:item},function(data){
    if(obj.parents('.cart_store_products').find("tbody tr").length==1){
    obj.parents('table').remove();
    }else{
    obj.parents('tr').remove();
    }
    if($(".cart_store_products").length>0){
    resetSelectProductTotal();
    }else{
    location.reload();
    }
    },'json');
    }else{
    return false;
    }
    });
    //清空购物车
    $(".shopcart_deleSele").click(function(){
    var cart_keys = new Array();
    $(".cart_store_products").find(".groupSelect").each(function() {
    if ($(this).is(":checked")) {
    cart_keys.push($(this).val());
    }
    });
    if(cart_keys.length==0){
    alert("您还没有选择商品！");
    return;
    }
    if(confirm("确定要清空购物车吗？")){
    $.post('/cart/remove',{data:cart_keys},function(data){
    resetSelectProductTotal();
    location.reload();
    },'json');
    }else{
    return false;
    }
    });
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_END);
?>