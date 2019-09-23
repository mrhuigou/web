<?php
use yii\helpers\Html;
use \common\component\Helper\Helper;
use yii\helpers\Url;

$this->title = "搜索页";
?>
    <header class="header w" id="header">
        <a class="pa-lt iconfont leftarr" href="javascript:history.back();" ></a>
        <div class="pr pl30 pr5 ">
            <form action="<?php echo \yii\helpers\Url::to(['/search/index'])?>" method="get" id="search_form">
                <input class="input-text minput w " id="searchBox"  type="text" name="keyword" value="<?=Html::encode($keyword)?>" autocomplete="off" tabindex="1">
                <a href="javascript:void(0)" class="search-btn iconfont">&#xe601;</a>
            </form>
        </div>
    </header>
	<div class="content">
		<ul class="filter  redFilter five clearfix" style="border-bottom: 2px solid #ff463c;">
			<li class="<?= $sort_selected == 'score' ? 'cur' : '' ?>"><a href="<?= $sort_data['score'] ?>">综合</a></li>
			<li class="<?= $sort_selected == 'record' ? 'cur' : '' ?>"><a href="<?= $sort_data['record'] ?>">销量</a>
			</li>
			<li class="<?= $sort_selected == 'favourite' ? 'cur' : '' ?>"><a href="<?= $sort_data['favourite'] ?>">关注</a>
			</li>
			<li class="<?= $sort_selected == 'price' ? 'cur ' : ' ' ?>"><a href="<?= $sort_data['price'] ?>" class="vm">价格<i
						class="iconfont vm f12 white">
						<?= Yii::$app->request->get('order') == "desc" ? "&#xe61b;" : "&#xe619;" ?></i></a></li>
				<li class="red"><a href="javascript:;" class="open-popup" data-target="#full">筛选</a></li>
		</ul>
        <?php if(isset($filter_attr['category']) && $filter_attr['category']){?>
        <div class="tab-box tab-box1">
            <div class="tab-tit">
                <a href="javascript:;" class="tab-tit-tri">
                    <span class="btn btn-s btn-gray" id="brand">品牌 <i class="iconfont">&#xe61b;</i></span>
                </a>
                <a href="javascript:;" class="tab-tit-tri">
                    <span class="btn btn-s btn-gray" id="size">分类 <i class="iconfont">&#xe61b;</i></span>
                </a>
            </div>
            <div class="tab-con">
                <div class="tab-con-list" style="display: none;">
	                <?php if(isset($filter_attr['brand'])){?>
                        <div class="which clearfix mb10">
			                <?php foreach ($filter_attr['brand'] as $v) { ?>
                                <a class="item <?=$v['selected']?"cur":''?> filter_item_attr" href="javascript:;" data-content="<?= $v['value'] ?>" data-content-id="<?= $v['code'] ?>">
					                <?= $v['name'] ?> <span class="red">(<?= $v['count'] ?>)</span>
                                    <i class="iconfont"></i>
                                </a>
			                <?php } ?>
                        </div>
                        <!-- 点击确认刷新 -->
                        <div class="flex-col">
                            <div class="flex-item-6">
                                <a class="btn btn-l btn-gray w filter_reset">重置</a>
                            </div>
                            <div class="flex-item-6">
                                <a class="btn btn-l btn-green w filter_confirm">确认</a>
                            </div>
                        </div>
	                <?php }?>
                </div>
                <div class="tab-con-list" style="display: none;">
                        <div class="which clearfix mb10">
			                <?php foreach ($filter_attr['category'] as $v) { ?>
                                <a class="item <?=$v['selected']?"cur":''?> filter_item_attr" href="javascript:;" data-content="<?= $v['value'] ?>" data-content-id="<?= $v['code'] ?>">
					                <?= $v['name'] ?>
                                    <i class="iconfont"></i>
                                </a>
			                <?php } ?>
                        </div>
                        <!-- 点击确认刷新 -->
                        <div class="flex-col">
                            <div class="flex-item-6">
                                <a class="btn btn-l btn-gray w filter_reset">重置</a>
                            </div>
                            <div class="flex-item-6">
                                <a class="btn btn-l btn-green w filter_confirm">确认</a>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    <?php }?>
        <?php if($models){ ?>
	        <?= $this->render("_item_view", [
		        'models' => $models,
                'page' => $page
	        ]) ?>
        <?php }else{?>
            <div class="p10 tc">当前没有任何商品</div>
        <?php } ?>
        <div class="weui-infinite-scroll " style="display: none;">
            <div class="infinite-preloader"></div>
            <span>正在加载...</span>
        </div>
	</div>


    <div id="full" class="weui-popup-container" style="z-index: 10000;">
        <div class="weui-popup-overlay"></div>
        <div class="weui-popup-modal ">
            <div class="w tc p10  f16 clearfix fx-top ">
                <a class="fr close-popup" href="javascript:;" >
                    <i class="aui-icon aui-icon-close  f28"></i>
                </a>
                筛选
            </div>
            <div style="top: 50px; bottom: 50px; overflow-y: scroll;" class="pa-t" >
		        <?php foreach ($filter_attr as $key => $value) { ?>
			        <?php if(isset($filter_attr['category']) && in_array($key,['brand','category'])){
				        continue;
			        }?>
                    <div class="p10 bg-wh bdb">
                        <h2><?= $key=="brand"?"品牌":$key ?></h2>
                        <div class="which clearfix">
					        <?php foreach ($value as $v) { ?>
                                <a class="item <?=$v['selected']?"cur":''?> filter_item_attr" href="javascript:;" data-content="<?= $v['value'] ?>" data-content-id="<?= $v['code'] ?>">
							        <?= $v['name'] ?> <span class="red">(<?= $v['count'] ?>)</span>
                                    <i class="iconfont"></i>
                                </a>
					        <?php } ?>
                        </div>
                    </div>
		        <?php } ?>
            </div>
            <div class="flex-col fx-bottom pa-b " >
                <div class="flex-item-6">
                    <a class="btn btn-l btn-gray w filter_reset" style="line-height: 50px;" >重置</a>
                </div>
                <div class="flex-item-6">
                    <a class="btn btn-l btn-green w filter_confirm" style="line-height: 50px;" >确认</a>
                </div>
            </div>
        </div>
    </div>
<?= h5\widgets\MainMenu::widget(); ?>


<script>
<?php $this->beginBlock('JS_END') ?>

$("body").on('click',".filter_item_attr",function(){
    if($(this).hasClass("cur")){
        $(this).removeClass("cur");
    }else{
        $(this).addClass("cur").siblings().removeClass("cur");
    }
});
$("body").on('click','.filter_reset',function(){
    $("body").find(".filter_item_attr").each(function(){
        $(this).removeClass("cur");
    });
});
$("body").on('click','.filter_confirm',function(){
    var keyword='<?=$keyword?>';
    if(keyword){
        var search_url="<?php echo \yii\helpers\Url::to(['/search/index','keyword'=>$keyword])?>";
    }else{
        var search_url="<?php echo \yii\helpers\Url::to(['/search/index','t'=>1])?>";
    }
    $("body").find(".filter_item_attr").each(function(){
        if($(this).hasClass("cur")){
            search_url=search_url+"&"+$(this).attr("data-content-id")+"="+$(this).attr("data-content");
        }
    });
    location.href=search_url;
});
var loading = false;
var page_total=<?=$page_total?>;
if(page_total>1){
    $(".weui-infinite-scroll").show();
}
var page=2;
$(document).ready(function () {
    if($.cookie('histroy_page_this')){
        //alert("listener has come!");
        page_value = $.cookie('histroy_page_this');
        page_value = parseInt(page_value);
        console.log('page_value:'+page_value);

        scrolltop_value = $.cookie('histroy_scrolltop');
        console.log('histroy_scrolltop:'+ scrolltop_value);
        if(page_value > 1){
            var post_url = "<?=Yii::$app->request->getAbsoluteUrl()?>";
            for(var i = 2; i <= page_value;i++){

                console.log('i:'+i);
                    $.ajax({
                        type: "POST",
                        url: post_url,
//                      cache:false,
                        async:false,
                        data: {page:i,_csrf:'<?php echo Yii::$app->request->csrfToken;?>'},
                        success: function(result){
                           // alert(j);
                            $("#list").append(result);
                            console.log('j2:'+i);
                            if( i >=  page_value){
                                var scrollcontent = $('.content');
                                scrollcontent.scrollTop(scrolltop_value);
                            }
                        }
                    });
            }
            page = parseInt(page_value) + 1;
        }else{
            var scrollcontent = $('.content');
            scrollcontent.scrollTop(scrolltop_value);
        }
        $.cookie("histroy_page_this",null);
        $.cookie("histroy_scrolltop",null);
    }
});


$('.urlgoto').bind('click',function () {

    var page_this = $(this).attr('data-content');
    var scrollcontent = $('.content');
    <?php $params = Yii::$app->request->get();
    unset($params['page']);
    unset($params['scrolltop']);
    ?>
//alert("set cookies");

    var date = new Date();
    date.setTime(date.getTime()+3600*1000);//只能这么写，表示一小时过期
    $.cookie('histroy_page_this', page_this, {expires: date});
    $.cookie('histroy_scrolltop', scrollcontent.scrollTop(), {expires: date});
    console.log('histroy_scrolltop:'+ scrollcontent.scrollTop());
    //alert("get cookies histroy_page_this = "+ $.cookie('histroy_page_this'));
    location.href = $(this).attr('data-filter');
});

$.backtop(".content");



$(".content").infinite().on("infinite", function() {
    if(!$.cookie('histroy_page_this')){
        var page_url="<?=Yii::$app->request->getAbsoluteUrl()?>";
        if(page<=page_total){
            if(loading) return;
            loading = true;
            $.post(page_url,{page:page},function(result){
                $("#list").append(result);
                loading = false;
                page++;
            });
        }else{
            $(".weui-infinite-scroll").find("div").removeClass("infinite-preloader");
            $(".weui-infinite-scroll").find("span").html("已经到最底了");
        }
    }
});


<?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);

$this->registerJsFile('https://g.alicdn.com/opensearch/opensearch-console/0.16.0/scripts/jquery-ui-1.10.2.js',['depends'=>['h5\assets\AppAsset'],'position' => \yii\web\View::POS_END]);
$this->registerCssFile('https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css',['depends'=>['h5\assets\AppAsset'],'position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('/assets/script/auto_product_name.js',['depends'=>['h5\assets\AppAsset'],'position' => \yii\web\View::POS_END]);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_END);
?>