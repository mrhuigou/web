<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 15:55
 */
$this->title = "休闲食品专区";
?>
<style>
	html {
		font-size: 20px;
	}
	.item-name{height: 1.3em;}
	.item-inner{background-color: #fff;border:1px solid #ddd;}
	.item-detail{padding-top: 15px;}
    .item-photo{margin-bottom: 0px;}
	.p-1{display: none;}
	.item-btn{
		margin-top: -34px;
		float: right;
	}
</style>
<header class="header w">
	<a class="pa-lt iconfont leftarr" href="javascript:history.back();"></a>
	<div class="pr pl30 pr5 ">
		<form action="<?php echo \yii\helpers\Url::to(['/search/index'])?>" method="get" id="search_form">
			<input class="input-text minput w " type="text" name="keyword"
			       value="<?= \yii\helpers\Html::encode(Yii::$app->request->get('keyword')) ?>" autocomplete="off"
			       tabindex="1">
			<a href="javascript:void(0)" class="search-btn iconfont">&#xe601;</a>
		</form>
	</div>
</header>
<div class="content">
	<section class="veiwport" style="max-width: inherit;">
		<!-- 活动焦点图 -->
		<?php if ($swiper) { ?>
			<div class="swiper-container" id="swiper-container_banner">
				<div class="swiper-wrapper">
					<?php foreach ($swiper as $value) { ?>
						<div class="swiper-slide">
							<a href="<?= \yii\helpers\Url::to($value->link_url) ?>">
								<img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class=" w ">
							</a>
						</div>
					<?php } ?>
				</div>
				<!-- Add Pagination -->
				<div class="swiper-pagination swiper-pagination-white swiper-pagination-banner"></div>
			</div>
		<?php } ?>
		<?php if ($ad_1) { ?>
		<!-- 商品列表 -->
		<div class="Items">
			<div class="item-wrap item-hori" id="list">
				<?php foreach ($ad_1 as $value) { ?>
					<div class="item">
						<div class="item-padding-3">
							<div class="item-inner">
								<div class="item-photo">
									<a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="db w">
										<img src="<?= \common\component\image\Image::resize($value->product->image,200,200) ?>" alt="" class="db w"/>
									</a> <!--已售罄-->
									<?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
								</div>
								<div class="item-detail">
									<a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="item-name">  <?=$value->product->description->name?> </a>
									<div class="item-des green">
										<?=$value->product->description->meta_keyword?>
									</div>
									<div class="item-price">
										<div class="item-price-2">
											<span class="p-2">￥</span><span class="p-3"><?=$value->product->getPrice()?></span>
										</div>
										<a class="item-btn btn btn-s btn-red" href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>">去购买</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<?php if ($ad_2) { ?>
		<!-- 广告位 -->
		<div class="flex-col mt5 mb10">
			<?php foreach ($ad_2 as $value) { ?>
			<div class="flex-item-6">
				<a href="<?= \yii\helpers\Url::to($value->link_url) ?>"><img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w"></a>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if ($brand) { ?>
			<!-- 广告位 -->
			<div class="flex-col mt5 mb10">
				<?php foreach ($brand as $value) { ?>
					<div class="flex-item-4">
						<a href="<?= \yii\helpers\Url::to($value->link_url) ?>"><img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w"></a>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

		<!-- 分类导航 -->
		<div class="tab-box pr" id="menu-nav">
			<div class="tab-tit normal-items x os-x normal-items1 f16">
				<a href="javascript:void(0)" class="tab-tit-tri  item cur">休闲零食</a>
				<a href="javascript:void(0)" class="tab-tit-tri item">饼干糕点</a>
				<a href="javascript:void(0)" class="tab-tit-tri item">糖巧</a>
				<a href="javascript:void(0)" class="tab-tit-tri item">干果蜜饯</a>
				<a href="javascript:void(0)" class="tab-tit-tri item">肉干肉脯</a>
				<a href="javascript:void(0)" class="tab-tit-tri item">冲调茗茶</a>
				<a href="javascript:void(0)" class="tab-tit-tri item">滋补养生 </a>
			</div>
            <i class="iconfont pa-tr t10 pt2 f12 red bg-wh">&#xe68c;</i>
			<div class="tab-con" style="font-size: 14px;">
				<div class="tab-con-list">
					<!-- 小分类 -->
					<div class="clearfix bg-wh pt10 pr5 sub_cate">
						<a href="javascript:void(0)" class="label label-pill label-default label-danger fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-1F01">薯片</a>
						<a href="javascript:void(0)" class="label label-pill label-default  fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-1F02">膨化小吃</a>
					</div>
				</div>
				<div class="tab-con-list" style="display: none;">
					<!-- 小分类 -->
					<div class="clearfix bg-wh pt10 pr5 sub_cate">
						<a href="javascript:void(0)" class="label label-pill label-default label-danger fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-2F01">饼干</a>
						<a href="javascript:void(0)" class="label label-pill label-default  fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-2F02">派</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-2F03">曲奇</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-2F04">威化</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-2F05">蛋糕</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-2F06">沙琪玛</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-2F07">酥饼</a>
					</div>
				</div>
				<div class="tab-con-list" style="display: none;">
					<!-- 小分类 -->
					<div class="clearfix bg-wh pt10 pr5 sub_cate">
						<a href="javascript:void(0)" class="label label-pill label-default label-danger fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-3F01">口香糖</a>
						<a href="javascript:void(0)" class="label label-pill label-default  fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-3F02">巧克力</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-3F03">糖果</a>
					</div>
				</div>

				<div class="tab-con-list" style="display: none;">
					<!-- 小分类 -->
					<div class="clearfix bg-wh pt10 pr5 sub_cate">
						<a href="javascript:void(0)" class="label label-pill label-default label-danger fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-4F01">干果</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-4F02">瓜子</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-4F03">红枣</a>
					</div>
				</div>

				<div class="tab-con-list" style="display: none;">
					<!-- 小分类 -->
					<div class="clearfix bg-wh pt10 pr5 sub_cate">
						<a href="javascript:void(0)" class="label label-pill label-default label-danger fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-5F01">肉脯</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-5F02">即食海产</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-5F03">多味果仁</a>
					</div>
				</div>
				<div class="tab-con-list" style="display: none;">
					<!-- 小分类 -->
					<div class="clearfix bg-wh pt10 pr5 sub_cate">
						<a href="javascript:void(0)" class="label label-pill label-default label-danger fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-6F01">咖啡</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-6F02">蜂蜜</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-6F03">麦片</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-6F04">奶粉</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-6F05">芝麻糊</a>
						<a href="javascript:void(0)" class="label label-pill label-default fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-6F06">豆奶粉</a>
					</div>
				</div>
				<div class="tab-con-list" style="display: none;">
					<!-- 小分类 -->
					<div class="clearfix bg-wh pt10 pr5 sub_cate">
						<a href="javascript:void(0)" class="label label-pill label-default label-danger fl ml10 mb10 sub_cate_item" data-content="H5-2LXS-7F01">阿胶</a>
					</div>
				</div>
			</div>
		</div>
		<!-- 商品列表 -->
		<div class="Items">
			<div class="item-wrap item-2" id="list2">
				<!--tpl-->
			</div>
		</div>
		<?=\h5\widgets\Block\Hongbao::widget(['code'=>'snacks'])?>
	</section>
</div>
<?= h5\widgets\MainMenu::widget(); ?>
<!-- 商品列表的模板 -->
<script id="tpl" type="text/html">
	<% for(var i=from; i<=to; i++) {%>
	<div class="item">
		<div class="item-padding">
			<div class="item-inner">
				<div class="item-photo">
					<a href="<%:=list[i].url%>"> <img src="<%:=list[i].image%>" alt="" class="db w" /> </a> <!--已售罄-->
					<% if(list[i].stock <=0){ %> <i class="item-tip iconfont">&#xe696;</i> <%}%>
				</div>
				<div class="item-detail">
					<a href="<%:=list[i].url%>" class="item-name"> <%:=list[i].name%> </a>
					<div class="item-des">
						<%:=list[i].meta_description%>
					</div>
					<div class="item-price">
						<div class="item-price-2">
							<span class="p-1">优惠价:</span><span class="p-2">￥</span><span class="p-3"><%:=list[i].cur_price%></span>
						</div>
						<div class="item-price-1" style="display:none;">
							￥<%:=list[i].vip_price%>
						</div>
						<a class="item-btn btn btn-s btn-red" href="<%:=list[i].url%>">去购买</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<% } %>
</script>

<?php $this->beginBlock('JS_END') ?>
$.backtop(".content");
var swiper_banner = new Swiper('#swiper-container_banner', {
pagination: '.swiper-pagination-banner',
paginationClickable: true,
spaceBetween: 0,
centeredSlides: true,
autoplay: 4000,
autoplayDisableOnInteraction: false
});

var index=$(".tab-tit-tri.cur").index();
var cur_obj=$(".tab-con-list").eq(index).find(".label-danger");
var cur_code=cur_obj.attr("data-content");
getData(cur_code);

$("body").on('click',".sub_cate_item",function(){
var code=$(this).attr("data-content");
var _this=$(this);
_this.addClass("label-danger").siblings().removeClass("label-danger");
getData(code);
});

$("body").on("click",".tab-tit-tri",function(){
var index=$(this).index();
var cur_obj=$(".tab-con-list").eq(index).find(".label-danger");
var cur_code=cur_obj.attr("data-content");
getData(cur_code);
});

var wx_xcx = <?php echo Yii::$app->session->get('source_from_agent_wx_xcx') ? 1:0  ?>;
function getData(code){
var source = getSourceParms();
var xhrurl="<?php echo Yii::$app->params['API_URL']?>/mall/v1/ad/product?"+source;
$.ajax({
type : "get",
async : false,
url :xhrurl,
cache : false,
dataType : "jsonp",
jsonp: "callback",
data:{code:code,r:Math.random(),wx_xcx:wx_xcx},
success : function(result){
if(result.data.length>0){
var tpl = $('#tpl').html();
var html= template(tpl, {list:result.data,from:0,to:result.data.length-1});
$("#list2").html(html);
}else{
$("#list2").html("<p class='p10 w lh200 mb5 tc'>暂时没有任何数据</p>");
}
}});
}
$("#menu-nav").scrollFix({container:$(".content"),zIndex : 99999,distanceTop:45});
Ad_Sys_Code();
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_END);
?>
