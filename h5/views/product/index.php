<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use \common\component\Helper\Helper;
use yii\helpers\Url;
$this->title = '商品详情';
?>
<?=\h5\widgets\Header::widget(['title'=>'商品详情'])?>
	<section class="veiwport">
		<div class="mb50">
			<div class="swiper-container" id="swiper-container_banner">
				<div class="swiper-wrapper">

                    <?php if ($model->promotion) { ?>
                        <?php foreach ($model->promotion as $promotion) { ?>
                            <?php if ($promotion->promotion_detail_image) { ?>
                                <div class="swiper-slide">
                                    <div class="item-photo">
                                        <img src="<?= \common\component\image\Image::resize($promotion->promotion_detail_image, 320, 320) ?>" class="w">
                                    </div>
                                </div>
                            <?php }?>
                        <?php }?>
                    <?php }?>

					<?php foreach ($model->imagelist as $value) { ?>
						<div class="swiper-slide">
                            <div class="item-photo">
							<img src="<?= \common\component\image\Image::resize($value, 320, 320) ?>" class="w">

                            </div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php if ($model->bedisplaylife) { ?>
				<div style="margin-top: -25px;position: absolute;z-index: 999;" class="pl5">
                    <?php if($product){?>
                        <span class="p2 greenbg bd-green white lh150  f12">生产日期：<?= $product->productDate ?></span>
                    <?php }else{?>
                        <?php if ($model->productDate) { ?>
                            <span class="p2 greenbg bd-green white lh150  f12">生产日期：<?= $model->productDate ?></span>
                        <?php } ?>
                    <?php } ?>
					<span class="p2 bd-green green whitebg lh150 f12">保质期 :<?= $model->life ?></span>
				</div>
			<?php } ?>
			<div class="whitebg bdt bdb p5 mb5">
				<!--标题-->
				<div class="flex-col">
					<div class="flex-item-10">
						<p class="lh150">
                            <?php if($model->baoyou){?><span class="bd-red red  br5">包邮</span><?php }?>
                            <?= Html::encode($model->description->name) ?> <span class="control-format">[<i class="format fb red"></i>]</span></p>

							<!--卖点-->
						<p class="gray9 f12 lh150 red">
                                <!--促销方案详情-->
                                <?php if ($model->promotion) { ?>
                                    <?php foreach ($model->promotion as $promotion) { ?>
                                            <?php if ($promotion->promotion_detail_title) { ?>
                                                <?= Html::encode('[促]'.$promotion->promotion_detail_title) ?>
                                            <?php }?>
                                    <?php }?>
                                <?php }?>
                                <!--优惠券详情-->
                                <?php if ($model->coupon) { ?>
                                    <?php foreach ($model->coupon as $coupon) { ?>
                                            <?= '[券]'.$coupon->comment; ?>
                                    <?php }?>
                                <?php }?>
                                <?php if ($model->description->meta_description) { ?>
                                    <?= Html::encode($model->description->meta_description) ?>
                                <?php } ?>
                        </p>
					</div>
					<div class="flex-item-2 bdl tc" onclick="addToWishList(<?=$model->product_base_id?>,'product');">
						<i class="iconfont org f18">&#xe62d;</i>

						<p class="f12 gray9">收藏</p>
					</div>
				</div>
				<div class="red fb">
					￥<span class="f20" id="cur_price"><?= $model->getPrice(); ?></span>
					<span class="del ml5  " id="sale_price"><?= $model->getSale_price(); ?></span>
				</div>
                <?php if($model->can_not_return){?>
                    <div class="mt5">
                        <span class="btn btn-xxs btn-bd-red">提示</span>
                        <span class="f12 org">该商品不支持7天无理由退换货</span>
                    </div>
        <?php }?>
			</div>
			<?= h5\widgets\Product\Coupon::widget(['model' => $model]) ?>
			<?= h5\widgets\Product\Promotion::widget(['model' => $model]) ?>
			<?= h5\widgets\Product\Shipping::widget(['model' => $model]) ?>
			<!--数量-->
			<div class="whitebg bdt bdb pt5 pb5 mb5">
				<?php foreach ($model->sku as $sku) { ?>
					<div class="flex-col f12 mb5">
						<div class="flex-item-2 tc gray9">
							<?= $sku['name'] ?>
						</div>
						<div class="flex-item-10">
							<div class="package J_TSaleProp">
								<?php foreach ($sku['content'] as $content) { ?>
									<a href="javascript:void(0)" class="sku"
									   attr_id="<?= $content['value'] ?>"> <?= $content['name'] ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="flex-col f12">
					<div class="flex-item-2 tc gray9 pt2">
						数量
					</div>
					<div class="flex-item-10">
						<span class="num-lower item-num-lower iconfont"></span>
						<input type="tel" min="1" max="100" name="qty" value="1" class="num-text item-num-text">
						<span class="num-add item-num-add iconfont"></span>
						<span  id="J_EmStock" class="vm tl ml5 lh200"></span>
					</div>
				</div>

			</div>

			<!--店鋪-->
			<div class="whitebg bdt bdb pt10 pb10 mb10 none">
				<div class="flex-col">
					<div class="flex-item-2 tr gray9 pr10">
						<i class="iconfont f25 mt2">&#xe635;</i>
					</div>
					<div class="flex-item-5 pt5">
						<a href="#" class="fb">青岛每日惠购店</a>
					</div>
					<div class="flex-item-5 tr pr15">
						<a href="#" class="btn greenbtn mbtn w100">进店逛逛</a>
					</div>
				</div>
			</div>
			<?= h5\widgets\Product\CouponRelation::widget(['model' => $model]) ?>
			<?= h5\widgets\Product\Relation::widget(['model' => $model]) ?>

			<div class='tabslet pb50'>
				<ul class='horizontal flex-col tc'>
					<li class="flex-item-6 active"><a href="#tab-1">商品详情</a></li>
					<li class="flex-item-6"><a href="#tab-2">商品属性</a></li>
				</ul>
				<div id='tab-1'>
					<div class="mt5 p10" style="width: 32rem;height: auto;overflow: hidden;">
						<?= Helper::ClearHtml(Html::decode($model->description->description)) ?>
					</div>
				</div>
				<div id='tab-2' >
					<div class="p10">
						<?php if ($model->attibute) { ?>
							<table class="tbp5 tb-bd w whitebg f12">
								<?php foreach ($model->attibute as $value) { ?>
									<tr>
										<td class="graybg" width="20%"><?= $value->attribute_name ? $value->attribute_name->name : "*"; ?></td>
										<td><?= $value->text; ?></td>
									</tr>
								<?php } ?>
							</table>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!--~~ 底部加入购物车 ~~-->
<?php if ($model->begift == 0) { ?>
	<div class="fx-bottom bs-top whitebg" style="z-index: 999">
		<div class="flex-col tc flex-center">
			<div class="flex-item-4 flex-col pt2">
				<a class="flex-item-6"
				   href="<?= \yii\helpers\Url::to(['/shop/index', 'shop_code' => $model->store_code]) ?>">
					<i class="iconfont f20">&#xe63f;</i>
					<p>首页</p>
				</a>
				<a class="flex-item-6 pr" href="<?= \yii\helpers\Url::to(['/cart/index']) ?>">
					<i class=iconfont>&#xe63b;</i>
					<p>购物车</p>
					<em class="info-point pa-rt r5 cart_qty" style="max-width: 22px;"><?= Yii::$app->cart->getCount() ?></em>
				</a>
			</div>
	<?php if($model->online_status){?>
		<?php if($model->getStockCount()>0){ ?>
			<a class="flex-item-4 flex-row flex-middle pt2 disabled graybg white f14" id="J_LinkBasket"   href="javascript:;" style="line-height: 52px;">
				加入购物车
			</a>
			<a class="flex-item-4 flex-row flex-middle pt2 disabled graybg white f14" id="J_LinkBuy" href="javascript:;"  style="line-height: 52px;">
				立即购买
			</a>
		<?php }else{?>
            <a class="flex-item-8 flex-row flex-middle pt2  graybg  f14" href="javascript:;"  style="line-height: 52px;">
                商品已售罄
            </a>
		<?php } ?>
        <?php }else{?>
        <a class="flex-item-8 flex-row flex-middle pt2  graybg  f14" href="javascript:;"  style="line-height: 52px;">
           商品已下架
        </a>
        <?php } ?>
		</div>
	</div>
<?php } ?>
<script>
<?php
$this->beginBlock('JS_SKU')
?>
//销售属性集
var product_base_id=<?= $model->product_base_id ?>;
var keys = eval(<?= json_encode($model->skuKeys) ?>);
var sku_datas = eval(<?= json_encode($model->SkuData) ?>);
//保存最后的组合结果信息
//alert(JSON.stringify(sku_datas));
var SKUResult = {};
var Sku='';
initSKU();
//alert(JSON.stringify(data));
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
            $('#cur_price').text(object.price);
            $('#sale_price').text(object.sale_price);
            var cur_discount=FloatSub(object.sale_price,object.price);
            $('#cur_discount').text('立省'+cur_discount+'元');
            if(!object.format){
                $(".control-format").hide();
            }else{
                $('.format').text(object.format);
            }
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

//alert('error:'+data+'===>'+data[selectedIds.join(';')] +"===>"+selectedIds.join(';')+"data=====>"+JSON.stringify(data));
            Sku="";
            $('#J_LinkBuy').addClass(" graybg").removeClass("redbg");
            $('#J_LinkBasket').addClass("disabled graybg").removeClass("orgbg");
        }
    } else {
        //设置属性状态
        Sku='';
        $('.sku').each(function() {
            SKUResult[$(this).attr('attr_id')] ? $(this).removeClass('disabled') : $(this).addClass("disabled").removeClass('cur');
        });

        $('#J_LinkBuy').addClass("disabled graybg").removeClass("redbg");
        $('#J_LinkBasket').addClass("disabled graybg").removeClass("orgbg");
    }
});
var cur_sku='<?= $cur_sku ?>';
$('.J_TSaleProp').each(function() {
    if(cur_sku){
        var sku_obj=cur_sku.split(";");
        $(this).children(":not('.disabled')").each(function(){
            if(sku_obj.indexOf($(this).attr("attr_id"))> -1){
                $(this).trigger('click');
            }
        });
    }else{
        var self = $(this).children(":not('.disabled')").first();
        if(self){
            self.trigger('click');
        }
    }
});
var swiper_banner = new Swiper('#swiper-container_banner', {
    pagination: '.swiper-pagination',
    paginationClickable: true,
    spaceBetween: 0,
    centeredSlides: true,
    autoplay: 4000,
    autoplayDisableOnInteraction: false
});
Ad_Sys_Base('<?=$model->product_base_code?>');
$('.tabslet').tabslet();

var referrer = document.referrer;

if(referrer.indexOf("mp.weixin") > 0 )
{
    if(location.search.indexOf("neednt_refresh")> 0){

    }else{
        if(location.search.indexOf("?")> 0){
            var reurl = location.protocol+'//'+location.host + location.pathname + location.search+"&neednt_refresh=1";
        }else{
            var reurl = location.protocol+'//'+location.host + location.pathname + location.search+"?neednt_refresh=1";
        }
        location.href = reurl;
    }
    //    $.getJSON('http://58.56.158.42:8086/test/get-message?callback=?',{date:"获取上一页url="+document.referrer + "&获取当前url="+window.location.href}, function(result){
    //    window.location.reload();
    //});
}
<?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['JS_SKU'], \yii\web\View::POS_END);
?>
<?=\h5\widgets\Tools\Share::widget([
        'data'=>[
	        'title' => $model->description->name,
	        'desc' => $model->description->meta_description?$model->description->meta_description:"每日惠购网，物美价廉，当天订单，当天送。",
	        'link' => Yii::$app->request->getAbsoluteUrl(),
	        'image' => \common\component\image\Image::resize($model->image, 200, 200)
//            'image' => Yii::$app->request->getHostInfo().'/assets/images/mrhuigou_logo.jpg'
        ]
])?>
<?//=\h5\widgets\Block\Share::widget();?>