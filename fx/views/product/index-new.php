<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use \common\component\Helper\Helper;
use yii\helpers\Url;
$this->title = $model->description->name;
?>
	<div class="tab-box">
		<header class="fx-top bs-bottom whitebg lh44">
			<div class="flex-col tc">
				<a class="flex-item-2" href="javascript:history.back();">
					<i class="iconfont">&#xe63d;</i>
				</a>
				<div class="flex-item-8 flex-col tab-tit">
					<a href="javascript:void(0);" class="flex-item-6  tab-tit-tri cur">商品</a>
					<a href="javascript:void(0);" class="flex-item-6 tab-tit-tri">详情</a>
				</div>
				<a class="flex-item-2" href="/">
					<i class="iconfont f28">&#xe63f;</i>
				</a>
			</div>
		</header>
		<section class="veiwport pb50">
			<div class="mt50 tab-con">
				<div class="tab-con-list">
					<div class="swiper-container" id="swiper-container_banner">
						<div class="swiper-wrapper">
							<?php foreach ($model->imagelist as $value) { ?>
								<div class="swiper-slide">
									<img src="<?= \common\component\image\Image::resize($value, 640, 640) ?>" class="w">
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="mb50 pr">
						<?php if($model->bedisplaylife){?>
						<div class="pro-date">
							<span class="fl">保质期 :<?=$model->life?></span>
							<?php if($model->productDate){?><span class="fr">生产日期：<?=$model->productDate?></span><?php }?>
						</div>
						<?php } ?>
						<!--价格-->
						<div class="flex-col tc flex-center bdb">
							<div class="flex-item-6 flex-row flex-middle white redbg " >
								<p class="f20 fb  tc " id="cur_price">
									<?= $model->getPrice(); ?>
								</p>
								<p class="del ml5 tc " id="sale_price"><?= $model->getSale_price(); ?></p>
							</div>
							<div class="flex-item-4  fb f16 whitebg red flex-row flex-center flex-middle" id="cur_discount" style="line-height: 49px;">
							</div>
							<div class="flex-item-2 flex-row whitebg flex-center flex-middle" onclick="addToWishList(<?=$model->product_base_id?>,'product');">
								<p class="iconfont yellow pb5">&#xe62d;</p>
								<p>收藏</p>
							</div>
						</div>
						<!--标题、卖点-->
						<div class="p10 whitebg">
							<h2 class="fb">
								<?= Html::encode($model->description->name) ?> [<i class="format fb red"></i>]
							</h2>
							<!--买点-->
							<p class="red f12 mt5"><?= Html::encode($model->description->meta_description) ?></p>
                        <?php if($model->can_not_return){?>
                            <div class="mt5">
                                <span class="btn btn-xxs btn-bd-red">提示</span>
                                <span class="f12 org">该商品不支持7天无理由退换货</span>
                            </div>
                        <?php }?>
						</div>
						<?= fx\widgets\Product\Coupon::widget(['model' => $model]) ?>
						<?= fx\widgets\Product\Promotion::widget(['model' => $model]) ?>
						<div class="whitebg bdb mt5" >
							<?php foreach ($model->sku as $sku) {
								?>
								<div class="flex-col tc flex-center bdt">
									<div class="flex-item-3 flex-col flex-center graybg flex-middle p5 gray" style="line-height: 25px;"><?= $sku['name'] ?>
									</div>
									<div class="package flex-item-9 flex-col flex-left flex-middle J_TSaleProp p5" style="line-height: 25px;">
										<?php foreach ($sku['content'] as $content) { ?>
											<a href="javascript:void(0)" class="sku"   attr_id="<?= $content['value'] ?>"><?= $content['name'] ?></a>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							<div class="flex-col tc flex-center bdt ">
								<div class="flex-item-3 flex-col flex-center graybg flex-middle p5 gray" style="line-height: 50px;">数量
								</div>
								<div class="package flex-item-9 flex-col flex-left flex-middle J_TSaleProp p5" style="line-height: 30px;">
									<p class="clearfix  pl5 pr20 vm">
										<span class="num-lower item-num-lower iconfont"></span>
										<input type="tel" min="1" max="100" name="qty" value="1" class="num-text item-num-text">
										<span class="num-add item-num-add iconfont"></span>
									</p>
									<p  id="J_EmStock" class="vm tl"></p>
								</div>
							</div>
						</div>
						<?=fx\widgets\Product\Relation::widget(['model'=>$model])?>
					</div>

				</div>
				<!--数量-->
				<div class="tab-con-list whitebg pb50 " style="display: none">
					<?php if($model->attibute){ ?>
						<dl class="p10 whitebg bd">
							<dt>
								商品属性
							</dt>
							<dd >
								<?php foreach($model->attibute as $value){
									?>
									<span class=" f12 mt10 "> <label class="fb f12 p10"><?=$value->attribute_name?$value->attribute_name->name:"*";?>:</label><?=$value->text;?></span>
								<?php } ?>
							</dd>
						</dl>
					<?php }?>
					<div class="mt5 p10" style="width: 32rem;height: auto;overflow: hidden;">
						<?=Helper::ClearHtml(Html::decode($model->description->description))?>
					</div>
				</div>
			</div>
		</section>
	</div>
	<!--~~ 底部加入购物车 ~~-->
<?php if($model->begift==0){?>
	<div class="fx-bottom bs-top whitebg" style="z-index: 1000">
		<div class="flex-col tc flex-center">
			<div class="flex-item-4 flex-col pt2">
				<a class="flex-item-6"
				   href="<?= \yii\helpers\Url::to(['/shop/index', 'shop_code' => $model->store_code]) ?>">
					<i class="iconfont f20">&#xe635;</i>

					<p>店铺</p>
				</a>
				<a class="flex-item-6 pr" href="<?= \yii\helpers\Url::to(['/cart/index']) ?>">
					<i class=iconfont>&#xe63b;</i>

					<p>购物车</p>
					<em class="info-point pa-rt r5 cart_qty"><?= Yii::$app->fxcart->getCount() ?></em>
				</a>
			</div>
			<a class="flex-item-4 flex-row flex-middle pt2 disabled graybg white f14" id="J_LinkBasket" href="javascript:;" style="line-height: 42px;">
				加入购物车
			</a>
			<a class="flex-item-4 flex-row flex-middle pt2 disabled graybg white f14" id="J_LinkBuy" href="javascript:;" style="line-height: 42px;">
				立即购买
			</a>
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
var data = eval(<?= json_encode($model->SkuData) ?>);
//保存最后的组合结果信息
var SKUResult = {};
var Sku='';
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
        if(data[selectedIds.join(';')]){
            var object = data[selectedIds.join(';')];
            Sku=selectedIds.join(';');
            $('#cur_price').text(object.price);
            $('#sale_price').text(object.sale_price);
            var cur_discount=FloatSub(object.sale_price,object.price);
            $('#cur_discount').text('立省'+cur_discount+'元');
            $('.format').text(object.format);
            if(object.count>0){

                $('#J_EmStock').text('库存'+object.count+'件');
                $('#J_LinkBuy').removeClass("disabled graybg").addClass("redbg");
                $('#J_LinkBasket').removeClass("disabled graybg").addClass("orgbg");
            }else{
                alert("库存不足");
                $('#J_EmStock').text('库存不足');
                $('#J_LinkBuy').addClass("disabled graybg").removeClass("redbg");
                $('#J_LinkBasket').addClass("disabled graybg").removeClass("orgbg");
            }
        }else{
            alert("错误1");
            Sku="";
            $('#J_LinkBuy').addClass(" graybg").removeClass("redbg");
            $('#J_LinkBasket').addClass("disabled graybg").removeClass("orgbg");
        }
    } else {
        alert("错误2");
        //设置属性状态
        Sku='';
        $('.sku').each(function() {
            SKUResult[$(this).attr('attr_id')] ? $(this).removeClass('disabled') : $(this).addClass("disabled").removeClass('cur');
        })
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
<?=\fx\widgets\Tools\Share::widget([
	'data'=>[
		'title' => Html::encode($model->description->name),
		'desc' => Html::encode($model->description->meta_description),
		'link' => Yii::$app->request->getAbsoluteUrl(),
		'image' => \common\component\image\Image::resize($model->image, 200, 200)
	]
])?>