<?php
use yii\helpers\Url;

$this->title = ($model->description ? $model->description->name : '') . '---每日惠购（mrhuigou.com）-青岛首选综合性同城网购-发现达人体验-分享同城生活';
?>
<div id="detail" class="tshop-pbsm-tmall-srch-list">
	<div id="J_DetailMeta" class="tm-detail-meta tm-clear">
		<div class="tm-clear">
			<div class="tb-property">
				<div class="tb-wrap">
					<div class="tb-detail-hd">
						<h1>
							<?php if ($model->bepresell) { ?> <span
								class="red none">[预售]</span><?php } ?><?= $model->description->name ?> [<i class="J_Format" ></i>]
						</h1>

						<p>
							<?= $model->description->meta_description ?>
						</p>
					</div>
					<!--引入normalBasic-->
					<div class="tm-fcs-panel pt5">
						<dl class="tm-promo-panel tm-promo-cur" id="J_PromoPrice" data-label="当前价">
							<dt class="tb-metatit tm-promo-type" style="width:60px;">促 销 价：</dt>
							<dd>
								<div class="tm-promo-price">
									<em class="tm-yen">¥</em><span class="tm-price"><?= $model->getPrice(); ?></span>
									<em class="tm-promo-type  none" id="free-shipping">包邮</em>
									<em class="tm-promo-type  none" id="promotion"><s></s>促销</em>
								</div>
							</dd>
						</dl>
						<dl class="tm-price-panel tm-price-cur" id="J_StrPriceModBox" data-label="会员价">
							<dt class="tb-metatit " style="width:60px;">会 员 价：</dt>
							<dd>
								<div class="tm-price">
									<em class="tm-yen">¥</em> <span
										class="tm-price"><?= $model->getSale_price(); ?></span>
								</div>
							</dd>
						</dl>
						<?= frontend\widgets\Product\Promotion::widget(['model' => $model]) ?>
					</div>
					<div class="tb-key">
						<div class="tb-skin">
							<div class="tb-sku">
								<dl class="tb-prop tm-sale-prop tm-clear">
									<dt class="tb-metatit">商品规格</dt>
									<dd class="J_Format" style="margin-top:4px;"></dd>
								</dl>
								<?php if ($model->sku) {
									foreach ($model->sku as $sku) { ?>
										<dl class="tb-prop tm-sale-prop tm-clear ">
											<dt class="tb-metatit"><?= $sku['name'] ?></dt>
											<dd>
												<ul data-property="<?= $sku['name'] ?>" class="tm-clear J_TSaleProp  ">
													<?php foreach ($sku['content'] as $content) { ?>
														<li class="sku" attr_id="<?= $content['value'] ?>"><a
																href="javascript:;"><span><?= $content['name'] ?></span></a><i>已选中</i>
														</li>
													<?php } ?>
												</ul>
											</dd>
										</dl>
									<?php }
								} ?>
								<?=frontend\widgets\Product\Relation::widget(['model'=>$model])?>
								<dl class="tb-amount tm-clear">
									<dt class="tb-metatit">数量</dt>
									<dd id="J_Amount">
                                        <span class="tb-amount-widget mui-amount-wrap">
                                        <input type="text" class="tb-text mui-amount-input" name="qty" value="1"
                                               maxlength="8" title="请输入购买量">
                                        <span class="mui-amount-btn">
                                            <span class="mui-amount-increase iconfont f12 tc">&#xe60a;</span>
                                            <span class="mui-amount-decrease iconfont  f12 tc"">&#xe60b;</span>
                                        </span>
										<span class="mui-amount-unit">件</span>
										</span>
										<em id="J_EmStock" class="tb-hidden"
										    style="display: inline;">库存<?= $model->stockCount ?>件</em>
										<span id="J_StockTips"></span>
									</dd>
								</dl>
								<div class="tb-action tm-clear">
									<?php if (!$model->online_status) { ?>
										<div class="tb-btn-buy tb-btn-sku">
											<a href="javascript:;" rel="nofollow" title="此商品已下架！">此商品已下架！</a>
										</div>
									<?php } else { ?>
										<?php if ($model->getStockCount() > 0) { ?>
											<div class="tb-btn-buy tb-btn-sku">
												<a id="J_LinkBuy" href="javascript:;" rel="nofollow"
												   class="noPost">立刻购买</a>
											</div>
											<div class="tb-btn-basket tb-btn-sku" id="detailpro">
												<a href="javascript:;"    rel="nofollow"    id="J_LinkBasket" class="noPost"><i class="iconfont">&#xe612;</i>加入购物车</a>
											</div>
										<?php } else { ?>
											<div class="tb-btn-buy tb-btn-sku">
												<a href="javascript:;" rel="nofollow" title="此商品已下架！">商品已售罄</a>
											</div>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tb-gallery">
				<div class="tb-booth product_bigImg">
					<span class="zoomIcon iconfont">&#xe607;</span>
					<a href="javascript:;" rel="nofollow">
						<img id="J_ImgBooth"
						     data-ks-imagezoom="<?= \common\component\image\Image::resize($model->defaultImage, 500, 500, 9) ?>"
						     alt="<?= $model->description->name ?>"
						     src="<?= \common\component\image\Image::resize($model->defaultImage, 500, 500) ?>">
					</a>
				</div>
				<?php if ($model->imagelist) { ?>
					<!--包装-->
					<div class="picScroll-left-list example1 big">
						<div class="hd clearfix">
							<a class="next">></a>
							<a class="prev"><</a>
						</div>
						<div class="bd">
							<ul class="picList clearfix" id="J_UlThumb">
								<?php foreach ($model->imagelist as $image) { ?>
									<li>
										<div class="pic">
											<a href="javascript:;">
												<img data-bigimagewidth="700" data-bigimageheight="700"
												     data-has-zoom="true"
												     data-ks-imagezoom="<?= \common\component\image\Image::resize($image, 500, 500, 9) ?>"
												     src="<?= \common\component\image\Image::resize($image, 62, 62) ?>">
											</a>
										</div>
									</li>
								<?php } ?>

							</ul>
						</div>
					</div>
				<?php } ?>
				<p class="tm-action tm-clear">
					<a id="J_AddFavorite"
					   onclick="addToWish(<?= $model->product_base_id ?>, <?php echo $model->store_id; ?>,'product')"
					   href="javascript:;" data-aldurl="#" class="favorite">
						<i class="iconfont ">&#xe614;</i><span>收藏商品</span></a>
					<span id="J_CollectCount"></span>
				</p>
			</div>
		</div>

	</div>
	<div class="layout grid-s5m0">
		<div class="col-main">
			<div class="main-wrap">
				<style>
					.itagList {
						display: none;
					}
				</style>
				<div id="J_TabBarBox" style="width: 788px;">
					<ul id="J_TabBar" class="tabbar tm-clear ">
						<li class="tm-selected"><a href="#description" rel="nofollow" data-index="0">商品详情</a></li>
						<li><a href="#J_Reviews" rel="nofollow" data-index="1">累计评价
								<em class="J_ReviewsCount" style="display: inline;"><?= $model->review ?></em></a>
						</li>
					</ul>
				</div>
				<div id="itagCon">
					<div id="description" class="itagList" style="display: block;">
						<?php if ($model->attibute) { ?>
							<div id="attributes" class="attributes">
								<div class="attributes-list" id="J_AttrList">
									<p class="attr-list-hd tm-clear none"><em>产品参数：</em></p>
									<ul id="J_AttrUL">
										<?php foreach ($model->attibute as $value) { ?>
											<li title="&nbsp;<?= $value->text ?>"><?= $value->attribute_base->description->name ?>
												:&nbsp;<?= $value->text ?></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						<?php } ?>
						<div class="J_DetailSection tshop-psm tshop-psm-bdetaildes">
							<h4 class="hd">商品详情</h4>

							<div class="content ke-post" style="height: auto;">
								<?= \yii\helpers\Html::decode($model->description->description) ?>
							</div>
						</div>
					</div>
					<div id="J_Reviews" class="itagList">

					</div>
				</div>
				<div class="tabbar-bg" style="display: none;"></div>
			</div>
		</div>
		<div class="col-sub">
			<div class="J_DcAsyn" id="J_DcShopArchive">
				<div id="side-shop-info">
					<div class="shop-intro">
						<h3 style="" class="hd oh">
							<div class="name">
								<a target="_blank"
								   href="<?= yii\helpers\Url::to(['store/index', 'shop_code' => $model->store_code]) ?>"
								   class="shopLink" title="<?= $model->shop->name ?>"><?= $model->shop->name ?></a>
							</div>
							<i></i>
						</h3>
						<div class="main-info">
							<div class="shopdsr-item">
								<div class="shopdsr-title">描 述</div>
								<div class="shopdsr-score shopdsr-score-up-ctrl">
									<span class="shopdsr-score-con">5.00</span>
								</div>
							</div>
							<div class="shopdsr-item">
								<div class="shopdsr-title">服 务</div>
								<div class="shopdsr-score shopdsr-score-up-ctrl">
									<span class="shopdsr-score-con">5.00</span>

								</div>
							</div>
							<div class="shopdsr-item">
								<div class="shopdsr-title">物 流</div>
								<div class="shopdsr-score shopdsr-score-up-ctrl">
									<span class="shopdsr-score-con">5.00</span>
								</div>
							</div>
						</div>
						<div class="btnArea">
							<a class="enterShop" target="_blank"
							   href="<?php echo yii\helpers\Url::to(['store/index', 'shop_code' => $model->store_code]) ?>">进店逛逛</a>
							<a class="favShop" onclick="addToWish(0,<?php echo $model->store_id; ?>,'store')"
							   href="javascript:void(0)">收藏店铺</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->beginBlock('JS_END') ?>
//销售属性集
var product_base_id=<?= $model->product_base_id ?>;
var keys = eval(<?= json_encode($model->skuKeys) ?>);
var data = eval(<?= json_encode($model->skuData) ?>);
//保存最后的组合结果信息
var SKUResult = {};
var Sku='';
initSKU();
function addToWish(product_id,store_id,type){
addToWishList(product_id,store_id,type);
}
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_END);
?>
<?php $this->beginBlock('JS_END') ?>
//初始化用户选择事件
$('.J_TSaleProp .sku').each(function() {
var self = $(this);
var attr_id = self.attr('attr_id');
if(!SKUResult[attr_id]) {
self.addClass("tb-out-of-stock");
}}).click(function() {
var self = $(this);
//选中自己，兄弟节点取消选中
self.not(".tb-out-of-stock").toggleClass('tb-selected').siblings().removeClass('tb-selected');
//已经选择的节点
var selectedObjs = $('.J_TSaleProp .tb-selected');
if(selectedObjs.length) {
//获得组合key价格
var selectedIds = [];
selectedObjs.each(function() {
selectedIds.push($(this).attr('attr_id'));
});
selectedIds.sort(function(value1, value2) {
return parseInt(value1) - parseInt(value2);
});
var len = selectedIds.length;
//用已选中的节点验证
$(".sku").not(selectedObjs).not(self).each(function() {
var siblingsSelectedObj = $(this).siblings('.tb-selected');
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
$(this).addClass("tb-out-of-stock").removeClass('tb-selected');
} else {
$(this).removeClass('tb-out-of-stock');
}
});
if(data[selectedIds.join(';')]){
var object=data[selectedIds.join(';')];
Sku=selectedIds.join(';');
if(object.shipping){
$("#free-shipping").show();
}else{
$("#free-shipping").hide();
}
if(object.promotion){
$("#promotion").show();
$("#J_PromoPrice dt").text('促 销 价：');
$("#J_StrPriceModBox dt").text('会 员 价：');
}else{
$("#J_PromoPrice dt").text('会 员 价：');
$("#J_StrPriceModBox dt").text('市 场 价：');
$("#promotion").hide();
}
$('.J_Format').text(object.format);
$('#J_StrPriceModBox .tm-price').text(object.sale_price);
$('#J_PromoPrice .tm-price').text(object.price);
if(object.count>0){
$('#J_EmStock').text('库存'+object.count+'件');
$('#J_LinkBuy').removeClass("noPost");
$('#J_LinkBasket').removeClass("noPost");
}else{
$('#J_EmStock').text('库存不足');
$('#J_LinkBuy').addClass("noPost");
$('#J_LinkBasket').addClass("noPost");
}
}else{
Sku="";
$('#J_LinkBuy').addClass("noPost");
$('#J_LinkBasket').addClass("noPost");
}
}else{
Sku='';
$('#J_LinkBuy').addClass("noPost");
$('#J_LinkBasket').addClass("noPost");
$('.sku').each(function() {
SKUResult[$(this).attr('attr_id')] ? $(this).removeClass('tb-out-of-stock') : $(this).addClass('tb-out-of-stock').removeClass('tb-selected');
})
}
});
var cur_sku='<?= $cur_sku ?>';
$('.J_TSaleProp').each(function() {
if(cur_sku){
var sku_obj=cur_sku.split(";");
$(this).children(":not('.tb-out-of-stock')").each(function(){
if(sku_obj.indexOf($(this).attr("attr_id"))> -1){
$(this).trigger('click');
}
});
}else{
var self = $(this).children(":not('.tb-out-of-stock')").first();
if(self){
self.trigger('click');
}
}
});
$(".picScroll-left-list.big").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",scroll:6,vis:6,trigger:"click"});
$(".picScroll-left-list.big").each(function(){
var len=$(this).find("li").length;
if(len>6){
$(this).find(".hd").show()
}
});
$("#J_Reviews").load('<?=Url::to(['/product/review','product_base_id'=>$model->product_base_id])?>');
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
$this->registerCssFile("/assets/stylesheets/detail.css");
$this->registerJsFile("/assets/script/base_detail.js", ['depends' => [\frontend\assets\AppAsset::className()]]);
?>

