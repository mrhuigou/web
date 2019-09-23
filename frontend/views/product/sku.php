<!--~~ 加入购物车、立即购买弹层 ~~-->
<div class="cart-pop" style="display:none;">
    <div class="cart-top-goods clearfix">
        <a href="javascript:;" class="fl">
            <img src="<?=$product_base->image?>" alt="tu" width="50" class="db bc">
        </a>
        <p class="fl pr5">
            <a href="<?=\yii\helpers\Url::to(['/product/index','product_base_id'=>$product_base->product_base_id])?>" class="mxh20 f12 db"><?=$product_base->description->name?></a>
            <span class="f14 red fb db" id="J_VipPrice">￥<em class="price"><?=$product_base->price?></em></span>
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
            <span class="num-lower iconfont"></span>
            <input type="text" class="num-text" value="1" name="qty">
            <span class="num-add iconfont"></span>
        </p>
        <em id="J_EmStock"  style="display: inline;">库存<?=$product_base->stockCount?>件</em>
    </div>
    <input type="hidden" value="<?=Yii::$app->request->getCsrfTokenFromHeader()?>" name="_csrf">
    <div class="footer-cart clearfix static">
      <button type="button" id="J_LinkBuy" class=" btn mbtn redbtn w-per47 fl ">立即购买</button>
        <button type="button"  id="J_LinkBasket" class="btn mbtn w-per47 redbtn fr">加入购物车</button>
    </div>

<script>
//销售属性集
var product_base_id=<?=$product_base->product_base_id?>;
var keys = eval(<?=json_encode($data['sku_keys'])?>);
var data = eval(<?=json_encode($data['sku_data'])?>);
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
}).not('.disabled').click(function() {
    var self = $(this);
    //选中自己，兄弟节点取消选中
    self.toggleClass('cur').siblings().removeClass('cur');
    //已经选择的节点
    var selectedObjs = $('.J_TSaleProp .cur');
    if(selectedObjs.length) {
        //获得组合key价格
        var selectedIds = [];
        selectedObjs.each(function() {
            selectedIds.push($(this).attr('attr_id'));
        });
        var len = selectedIds.length;
        if(SKUResult[selectedIds.join(';')]){
            var prices = SKUResult[selectedIds.join(';')].prices;
            var maxPrice = Math.max.apply(Math, prices);
            var minPrice = Math.min.apply(Math, prices);
            var price=maxPrice > minPrice ? minPrice + "-" + maxPrice : maxPrice;
            $('#J_VipPrice .price').text(price);
            $('#J_EmStock').text('库存'+SKUResult[selectedIds.join(';')].count+'件');
            if(SKUResult[selectedIds.join(';')].count==0){
                $('#J_LinkBuy').addClass("disabled");
                $('#J_LinkBasket').addClass("disabled");
            }else{
                $('#J_LinkBuy').removeClass("disabled");
                $('#J_LinkBasket').removeClass("disabled");
            }
        }
        if(data[selectedIds.join(';')]){
            Sku=selectedIds.join(';');
        }else{
            Sku="";
        }
    } else {
        //设置属性状态
        Sku='';
        $('.sku').each(function() {
            SKUResult[$(this).attr('attr_id')] ? $(this).removeClass('disabled') : $(this).addClass("disabled").removeClass('cur');
        })
    }
});
    $('.package a:first').trigger('click');  
});

</script>
</div>