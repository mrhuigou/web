<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/1/22
 * Time: 15:28
 */
$this->title= $lottery&& !empty($lottery)?$lottery->title:"每日惠购 幸运大抽奖";
?>
<style type="text/css">
	* { margin: 0; padding: 0;}
	body { font-family: "Microsoft Yahei"; background: #d30d42}
	.rotary { position: relative;  width: 30rem; height: 30rem; margin: -1px 10px 10px 10px; background-image: url(/assets/images/choujiang/zp_new.png) ;background-repeat:no-repeat;background-size:contain;}
	.hand { position: absolute; left: 11.49rem; top:  11.49rem; width: 7rem; height: 7rem; cursor: pointer;}
</style>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="<?=\yii\helpers\Url::to(['/site/index'])?>">
            <i class="aui-icon aui-icon-home green f28"></i>
        </a>
        <div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($this->title) ?>
        </div>
        <a class="flex-item-2" href="<?=\yii\helpers\Url::to(['/user/index'])?>">
            <i class="iconfont green f28">&#xe603;</i>
        </a>
    </div>
</header>
<div class="pt50"></div>
<div class="clearfix">
<!--	<img src="/assets/images/choujiang/banner.png" class="w">-->
	<div class="rotary bc">
		<img class="hand" src="/assets/images/choujiang/z_1.png" alt="">
	</div>
</div>
<div id="my_result1">
    <?php if($my_self){?>
        <?php foreach ($my_self as $value){?>
    <div class="br5 opc-f p10 m10" id="my_self">
        <div class="flex-col activity-1-list">
            <div class="flex-item-2 tc">
                <img src="<?=\common\component\image\Image::resize($value->customer->photo,100,100)?>" alt="头像" width="47" height="47" class="img-circle">
            </div>
            <div class="flex-item-6 pl10">
                <p class="pt5"><?=$value->customer->nickname?></p>
<!--                <p class="gray6 f12 pt2">--><?//=date('m/d H:i:s',$value->creat_at)?><!--</p>-->
                <?php
                $coupon_id = $value->prize->coupon->coupon_id;
                $customer_id = $value->customer->customer_id;
                $customer_coupon_info = \api\models\V1\CustomerCoupon::find()->where(['customer_id'=>$customer_id,'coupon_id'=>$coupon_id,'from_lottery_result_id'=>$value->id])->orderBy('customer_coupon_id desc')->one();
                ?>
                <?php if($customer_coupon_info){?>
                    <?php if($customer_coupon_info->is_use == 2){?>
                        <p class="gray6 f12 pt2">已使用</p>
                    <?php }elseif($customer_coupon_info->is_use == 0 && $customer_coupon_info->end_time <= date('Y-m-d H:i:s')){?>
                        <p class="gray6 f12 pt2">已过期</p>
                    <?php }else{?>
                        <p class="gray6 f12 pt2">截止：<?=date('m-d',strtotime($customer_coupon_info->start_time))?>~<?=date('m-d H:i',strtotime($customer_coupon_info->end_time))?></p>
                    <?php }?>
                <?php }?>

            </div>
            <div class="flex-item-4 tr  org">
			    <?=$value->prize->title?>
            </div>
        </div>
    </div>
	    <?php } ?>
        <div class="m10 tc"><a class="btn lbtn greenbtn" id="lijishiyong" href="<?php echo \yii\helpers\Url::to(['/page/index','page_id'=>3538])?>">点击立即使用</a></div>
    <?php } ?>
</div>
<div id="my_result">
</div>
<!--<div class="m10 tc"><a class="btn lbtn greenbtn" href="/">点击立即使用</a></div>-->

<div class="pt10 pb30 whitebg">
<!--<div class="ad-container Items" id="ad-container"></div>-->
<script id="ad_content_tpl" type="text/html">
    <div class="item-wrap item-3"  id="cate1">
        <% for(var i=from; i<=to; i++) {%>
        <div class="item">
            <div class="item-padding">
                <div class="item-inner">
                    <div class="item-photo">
                        <a href="<%:=list[i].url%>"> <img src="<%:=list[i].image%>" alt="" class="db w"> </a> <!--已售罄-->
                    </div>
                    <div class="item-detail">
                        <a href="<%:=list[i].url%>" class="item-name"> <%:=list[i].name%> </a>
                        <div class="item-des"> 					<%:=list[i].meta_description%>				</div>
                        <div class="item-price">
                            <div class="item-price-2">
                                <span class="p-1">优惠价:</span><span class="p-2">￥</span><span class="p-3"><%:=list[i].cur_price%></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <% } %>
    </div>
</script>
<!--爆款-->
<div class="jb" style="padding-bottom: 0px;">
    <a href="<?php echo \yii\helpers\Url::to(['/page/index','page_id'=>1654])?>" class="t"></a>
    <div class="tit1  redtit1">
        <h2>爆品专区<a class="fr f12 red mt2" href="<?php echo \yii\helpers\Url::to(['/page/index','page_id'=>3538])?>">更多&gt;&gt;</a>
        </h2>
    </div>
    <div id="hot_content" style="max-width: 32rem;overflow: hidden;" class="pl5 pr5 bc"></div>
</div>
<script id="hot_content_tpl" type="text/html">
    <div class="swiper-container" id="swiper-container_ad">
        <div class="swiper-wrapper swiper-container-no-flexbox">
            <div class="swiper-slide clearfix row">
                <% for(var i=from; i<=to; i++) {%>
                <a href="<%:=list[i].url%>" class="db col-3 " >
                    <img src="<%:=list[i].image%>"  class="fl" style="height: 10.4rem;width: 10.4rem;border-left: 0.125rem solid #eee;border-bottom: 0.125rem solid #eee;">
                </a>
                <% if((i+1)%6==0 && (i+1)< to){ %>
            </div> <div class="swiper-slide clearfix row">
                <% } %>
                <% } %>
            </div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination swiper-pagination-ad" style="position: relative;"></div>
    </div>
</script>
</div>
<!--<div class="br5 opc-f p10 m10">-->
<!--	<div class="tit-- mt10 mb10">看看大家手气</div>-->
<!--    <div id="scrollDiv" style="height: 204px;min-height:68px;overflow: hidden;">-->
<!--	<ul id="user_list" >-->
<!--		--><?php //if($history){ ?>
<!--			--><?php //foreach ($history as $value){?>
<!--				<li class="flex-col activity-1-list">-->
<!--					<div class="flex-item-2 tc">-->
<!--						<img src="<?php // echo\common\component\image\Image::resize($value->customer->photo,100,100)?>" alt="头像" width="47" height="47" class="img-circle">-->
<!--					</div>-->
<!--					<div class="flex-item-6 pl10">-->
<!--						<p class="pt5"><?php // echo$value->customer->nickname?></p>-->
<!--						<p class="gray6 f12 pt2"><?php //echo date('m/d',$value->creat_at)?></p>-->
<!--					</div>-->
<!--					<div class="flex-item-4 tr  org">-->
<!--					<?php // echo $value->prize->title?>	-->
<!--					</div>-->
<!--				</li>-->
<!--			--><?php //} ?>
<!--		--><?php //}else{ ?>
<!--			<p class="tc lh200" id="result_prize">暂时没有中奖信息</p>-->
<!--		--><?php //} ?>
<!--	</ul>-->
<!--    </div>-->
<!--	 活动规则 -->
<!--	<div class="tit-- mt15 mb10">活动规则</div>-->
<!--	<ul class="ul ul-decimal ml25 f14">-->
<!--		<li>活动时间：2019-12-05 至 04-07</li>-->
<!--		<li>每个用户ID只能抽一次</li>-->
<!--		<li>购物无门槛使用，每次仅限一张</li>-->
<!--        <li>如有疑问请联系客服</li>-->
<!--	</ul>-->
<!--</div>-->
<script id="tpl" type="text/html">
    <div class="br5 opc-f p10 m10">
	<div class="flex-col activity-1-list" id="my_self">
		<div class="flex-item-2 tc">
			<img src="<%:=list.photo%>" alt="头像" width="47" height="47" class="img-circle">
		</div>
		<div class="flex-item-6 pl10">
			<p class="pt5"><%:=list.nickname%></p>
			<p class="gray6 f12 pt2"><%:=list.datetime%></p>
		</div>
		<div class="flex-item-4 tr org">
			<%:=list.des%>
		</div>
	</div>
    </div>
    <div class="m10 tc"><a class="btn lbtn greenbtn" href="<?php echo \yii\helpers\Url::to(['/page/index','page_id'=>3538])?>">点击立即使用</a></div>
</script>
<?php $this->beginBlock('J_Reviews') ?>
var $hand = $('.hand');
var time_count=0;
$hand.click(function(){
$.showLoading();
$.post('/choujiang/apply',{v:new Date().getTime(),'id':'<?=$id?>'},function(res){
$.hideLoading();
if(res.status){
time_count++;
rotateFunc(res.angle,res.message);
<!--window.location.href="https://m.mrhuigou.com/choujiang/index";-->
}else{
$.alert(res.message);
}
});
});
var rotateFunc = function(angle,text){
$hand.stopRotate();
$hand.rotate({
angle: 0,
duration: 5000,
animateTo: angle + 2880,
callback: function(){
$.alert(text);
<!--window.location.href="https://m.mrhuigou.com/choujiang/index";-->
loading();
$("#user_count").text(Number($("#user_count").text())+1);
}
});
};
var tpl = $('#tpl').html();
function loading(){
$.post('<?=\yii\helpers\Url::to(['/choujiang/result'])?>',{'lottery_id':'<?=$id?>'},function(res){
if(res){
var html= template(tpl, {list:res.data});
$("#lijishiyong").hide();
$("#my_result").html(html);
}
},'json');
}
$("#scrollDiv").Scroll({line:3,speed:1500,timer:3000});

wx_xcx = 0;
var source = getSourceParms();
var hot_content_tpl = $('#hot_content_tpl').html();
$.getJSON('<?php echo Yii::$app->params["API_URL"]?>/mall/v1/ad/index?code=H5-0F-AD&wx_xcx='+wx_xcx+'&callback=?&'+source, function(result){
var html= template(hot_content_tpl, {list:result.data,from:0,to:(result.data.length-1)});
$("#hot_content").html(html);
$("img.lazy").scrollLoading({container:$(".content")});
//爆款滑动
var swiper_ad = new Swiper('#swiper-container_ad', {
pagination: '.swiper-pagination-ad',
paginationType: 'progress',
paginationClickable: true,
autoplay: 6000,
loop:true,
});
});
var ad_content_tpl = $('#ad_content_tpl').html();
$.getJSON('<?php echo Yii::$app->params["API_URL"]?>/mall/v1/ad/product?code=H5-NEWS-DES1&'+wx_xcx+'&callback=?&'+source, function(result){
var html= template(ad_content_tpl, {list:result.data,from:0,to:(result.data.length-1)});
$("#ad-container").html(html);
$("img.lazy").scrollLoading({container:$(".content")});

});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJsFile("/assets/script/jquery.rotate.min.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/jquery.rowscroll.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJs($this->blocks['J_Reviews'],\yii\web\View::POS_END);
?>
<?php h5\widgets\Tools\Share::widget(['data'=>[
	'title' => '同城网购，尽在每日惠购！',
	'desc' => '现金红包大派送',
	'link' => \yii\helpers\Url::to(['/choujiang/index'],true),
	'image' => Yii::$app->request->getHostInfo() . '/assets/images/hongbao_share.jpg',
//    'hidden_status'=>'hidden',
]]);
?>
