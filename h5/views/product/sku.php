<!--~~ 加入购物车、立即购买弹层 ~~-->
<div class="cart-pop" style="display:none;">
    <div class="cart-top-goods clearfix">
        <a href="javascript:;" class="fl">
            <img src="<?=\common\component\image\Image::resize($product_base->defaultImage,50,50)?>"  width="50" class="db bc">
        </a>
        <p class="fl pr5">
            <a href="<?=\yii\helpers\Url::to(['/product/index','product_base_id'=>$product_base->product_base_id])?>" class="mxh20 f12 db"><?=$product_base->description->name?></a>
            <span class="f14 red fb db" id="J_VipPrice">￥<em class="price"><?=$product_base->getPrice()?></em> </span>
            <span class="f12 red  db"><?=$product_base->description->meta_description?></span>
        </p>
        <a href="javascript:;" class="close-pop iconfont">&#xe612;</a>
    </div>
    <?php if ($product_base->sku) {
        foreach($product_base->sku as $sku ) { ?>
    <div class="clearfix p10">
        <p class="fl f14"><?=$sku['name']?>：</p>
        <p class="package clearfix J_TSaleProp" data-property="<?=$sku['name']?>" >
            <?php foreach($sku['content'] as $content) { ?>
        <a href="javascript:void(0)" class="sku" attr_id="<?=$content['value']?>"><?=$content['name']?></a>
        <?php } ?>
        </p>
    </div>
    <?php
    }
    }
    ?>
    <p class="line"></p>
    <div class="clearfix p10">
        <p class="fl f14">数量：</p>
        <p class="clearfix fl pl5 pr20 vm">
            <span class="num-lower item-num-lower iconfont"></span>
            <input type="text" class="num-text item-num-text" value="1" name="qty" max="100" min="1">
            <span class="num-add item-num-add iconfont"></span>
        </p>
        <em id="J_EmStock"  style="display: inline;">库存<?=$product_base->stockCount?>件</em>
    </div>
    <div class="footer-cart clearfix static">
        <?php if($product_base->online_status){?>
            <?php if($product_base->getStockCount()>0){ ?>
              <button type="button" id="J_LinkBuy" class=" btn mbtn redbtn w-per47 fl ">立即购买</button>
                <button type="button"  id="J_LinkBasket" class="btn mbtn w-per47 orgbtn fr">加入购物车</button>
                <?php }else{?>
                <button type="button"  class=" btn mbtn graybtn w  ">商品已售罄</button>
                <?php } ?>
        <?php }else{ ?>
        <button type="button"  class=" btn mbtn graybtn w  ">商品已下架</button>
        <?php } ?>
    </div>

<script>
//销售属性集
var product_base_id=<?=$product_base->product_base_id?>;
var keys = eval(<?=json_encode($product_base->skuKeys)?>);
var sku_datas = eval(<?=json_encode($product_base->SkuData)?>);
//保存最后的组合结果信息
var SKUResult = {};
var Sku='';
//初始化用户选择事件
$(document).ready(function(){
initSKU();
$('.J_TSaleProp .sku').each(function() {
    var self = $(this);
    var attr_id = self.attr('attr_id');
    if(!SKUResult[attr_id]) {
        self.addClass("disabled");
    }else{
        if(SKUResult[attr_id] && SKUResult[attr_id].count==0 ){
            self.addClass("disabled");
        }
    }
}).click(function() {
    var self = $(this);
    //选中自己，兄弟节点取消选中
    self.not(".disabled").toggleClass('cur').siblings().removeClass('cur');
    //已经选择的节点
    var selectedObjs = $('.J_TSaleProp .cur');
    if(selectedObjs.length) {
        //获得组合key价格
        var selectedIds = [];
        selectedObjs.each(function() {
            selectedIds.push($(this).attr('attr_id'));
        });
        var len = selectedIds.length;

        //用已选中的节点验证
        $(".sku").not(selectedObjs).not(self).each(function() {
            var siblingsSelectedObj = $(this).siblings('.cur');
            var testAttrIds = [];//从选中节点中去掉选中的兄弟节点
            if(siblingsSelectedObj.length) {
                var siblingsSelectedObjId = siblingsSelectedObj.attr('attr_id');
                for(var i = 0; i < len; i++) {
                    (selectedIds[i] != siblingsSelectedObjId) && testAttrIds.push(selectedIds[i]);
                }
            } else {
                testAttrIds = selectedIds.concat();
            }
            testAttrIds = testAttrIds.concat($(this).attr('attr_id'));
            testAttrIds.sort(function(value1, value2) {
                return parseInt(value1) - parseInt(value2);
            });
            if(!SKUResult[testAttrIds.join(';')]) {
                $(this).addClass("disabled").removeClass('cur');
            } else {
                $(this).removeClass('disabled');
            }
        });
        if(sku_datas[selectedIds.join(';')]){
            var object = sku_datas[selectedIds.join(';')];
            Sku=selectedIds.join(';');
            $('#J_VipPrice .price').text(object.price);
            if(object.stock_type == 'NONE'){
                $('#J_LinkBuy').removeClass("disabled graybg").addClass("redbg");
                $('#J_LinkBasket').removeClass("disabled graybg").addClass("orgbg");
            }else if(object.stock_type == 'DESCRIPTION'){
                if(object.count>0 ){
                    if(object.count <= object.low_limit){
                        $('#J_EmStock').text('库存紧张');
                        $('#J_LinkBuy').removeClass("disabled graybg").addClass("redbg");
                        $('#J_LinkBasket').removeClass("disabled graybg").addClass("orgbg");
                    }else if(object.count>object.low_limit){
                        $('#J_EmStock').text('库存充足');
                        $('#J_LinkBuy').removeClass("disabled graybg").addClass("redbg");
                        $('#J_LinkBasket').removeClass("disabled graybg").addClass("orgbg");
                    }
                }else {
                    $('#J_EmStock').text('库存不足');
                    $('#J_LinkBuy').addClass("disabled graybg").removeClass("redbg");
                    $('#J_LinkBasket').addClass("disabled graybg").removeClass("orgbg");
                }
            }else{
                if(object.count>0){
                    $('#J_EmStock').text('库存'+object.count+'件');
                    $('#J_LinkBuy').removeClass("disabled graybg").addClass("redbg");
                    $('#J_LinkBasket').removeClass("disabled graybg").addClass("orgbg");
                }else{
                    $('#J_EmStock').text('库存不足');
                    $('#J_LinkBuy').addClass("disabled graybg").removeClass("redbg");
                    $('#J_LinkBasket').addClass("disabled graybg").removeClass("orgbg");
                }
            }
        }else{
            Sku="";
            $('#J_LinkBuy').addClass(" graybtn").removeClass("redbtn");
            $('#J_LinkBasket').addClass("disabled graybtn").removeClass("redbtn");
        }
    } else {
        //设置属性状态
        Sku='';
        $('.sku').each(function() {
            SKUResult[$(this).attr('attr_id')] ? $(this).removeClass('disabled') : $(this).addClass("disabled").removeClass('cur');
        })
        $('#J_LinkBuy').addClass("disabled graybtn").removeClass("redbtn");
        $('#J_LinkBasket').addClass("disabled graybtn").removeClass("redbtn");
    }
});
    $('.package').each(function() {
        var self = $(this).children(":not('.disabled')").first();
        if(self){
            self.trigger('click');
        }
    });
});

</script>
</div>