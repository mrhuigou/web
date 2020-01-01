<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/1/22
 * Time: 15:28
 */
$this->title="幸运大抽奖";
?>
<style type="text/css">
	* { margin: 0; padding: 0;}
	/*body { font-family: "Microsoft Yahei"; background: #d30d42}*/
	body { font-family: "Microsoft Yahei"; background: url(/assets/images/choujiang/bg_new2.jpg)
        background-size:100%;}
	.rotary { overflow:hidden;position: relative;  width: 30rem; height: 30rem; margin: -33rem 10rem 0rem 10rem; background-image: url(/assets/images/choujiang/zp_new.png) ;background-repeat:no-repeat;background-size:contain;}
	.hand { position: absolute; left: 11.8rem; top:  11.8rem; width: 7rem; height: 7rem; cursor: pointer;}
    .my_result1 {
        background: url(/assets/images/choujiang/bg_new2.jpg);
        /*background-size: 100%;padding-bottom: 10px;*/
        /*background-repeat:no-repeat;*/
        background-size:100%;
        /*margin-top: -1px;*/
        margin-top:2.9rem;
    }
    .my_result1 {
        background: url(/assets/images/choujiang/bg_new2.jpg);
        /*background-size: 100%;padding-bottom: 10px;*/
        /*background-repeat:no-repeat;*/
        background-size:100%;
        /*margin-top: -1px;*/
        margin-top:2.9rem;
    }
    .bg3 {
        background: url(/assets/images/choujiang/bg_new2.jpg);
        /*background-size: 100%;padding-bottom: 10px;*/
        /*background-repeat:no-repeat;*/
        background-size:100%;
        margin-top: -1px;
        /*margin-top:2.9rem;*/
    }
</style>
<!--<header class="fx-top bs-bottom whitebg lh44">-->
<!--    <div class="flex-col tc">-->
<!--        <a class="flex-item-2" href="/">-->
<!--            <i class="aui-icon aui-icon-home green f28"></i>-->
<!--        </a>-->
<!--        <div class="flex-item-8 f16">-->
<!--			--><?//= \yii\helpers\Html::encode($this->title) ?>
<!--        </div>-->
<!--        <a class="flex-item-2" href="--><?//=\yii\helpers\Url::to(['/user/index'])?><!--">-->
<!--            <i class="iconfont green f28">&#xe603;</i>-->
<!--        </a>-->
<!--    </div>-->
<!--</header>-->
<!--<div class="pt50"></div>-->
<div class="clearfix">
	<img src="/assets/images/choujiang/bg_new11.jpg" class="w">
	<div class="rotary bc">
		<img class="hand" src="/assets/images/choujiang/z_1.png" alt="">
	</div>
    <div class="my_result1">
    <div id="my_result" class="item-wrap" >
        <?php if($my_self){?>
            <?php foreach ($my_self as $value){?>
                <div class="br5 opc-f p10 m10" id="my_self">
                    <div class="flex-col activity-1-list">
                        <div class="flex-item-2 tc">
                            <img src="<?=\common\component\image\Image::resize($value->customer->photo,100,100)?>" alt="头像" width="47" height="47" class="img-circle">
                        </div>
                        <div class="flex-item-6 pl10">
                            <p class="pt5"><?=$value->customer->nickname?></p>
                            <p class="gray6 f12 pt2"><?=date('m/d H:i:s',$value->creat_at)?></p>
                        </div>
                        <div class="flex-item-4 tr  org">
                            <?=$value->prize->title?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
<!--    <div class="tc" style="margin-top: 10px"><a class="btn lbtn greenbtn" href="/">请于1月6号开始使用</a></div>-->
    </div>
</div>
<!--<div class="tc" style="text-align:center; background-image: url(/assets/images/choujiang/bg_new2.jpg); margin-top: -1px;font-size: 17px; color="#FFFFFF""><font >手气一般没关系，还有优惠送给你！</font> </div>-->
<div class="tc" style="text-align:center; background-image: url(/assets/images/choujiang/bg_new2.jpg);background-size:100%; margin-top: -1px;font-size: 17px; color:white">手气一般没关系，还有优惠送给你! </div>
<div class="pt10 pb30 bg3 ">
    <div class="ad-container Items" id="ad-container"></div>
    <div class="item-wrap item-2"  id="cate1">
    <?php if($coupon_info){?>
    <?php foreach ($coupon_info as $value){?>

            <div class="item">
                <div class="item-padding" >
<!--                    <div class="item-inner">-->
                        <div class="item-photo">
                           <a href="javascript:;" class="click_coupon" data-id="<?=$value->coupon->coupon_id?>" data-content="<?=$value->coupon->coupon_id?>"><img style="width: 13rem" src="<?= 'https://img1.mrhuigou.com/'.$value->img_url?>"  alt="" class="db w"></a>
<!--                           <a href="javascript:;" class="click_coupon" data-id="--><?//=$value->coupon->coupon_id?><!--" data-content="--><?//=$value->coupon->coupon_id?><!--"><img  style="width: 13rem" src="--><?//= 'https://img1.mrhuigou.com/group1/M00/06/A5/wKgB7l4LCEKAOT-PAAAdltzVv8M507.png'?><!--"  alt="" class="db w"></a>-->
                        </div>
<!--                    </div>-->
                </div>
            </div>

            <?php } ?>
            <?php } ?>
    </div>

    <div style="text-align: center; font-size: 22px;font-weight:bold;color: white;">
        每日<span style="color: #FFFF00">惠万家</span>  只需<span style="color: #FFFF00">新鲜达</span>
    </div>
    <div style="text-align:center; margin-top: -1px;font-size: 17px; color:white">市内四区配送入户，同城当日达</div>
    <div style="text-align:center; margin-top: -1px;font-size: 17px; color:white">客服电话：0532-55729957</div>
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
<!--<script id="tpl" type="text/html">-->
<!--    <div class="br5 opc-f p10 m10">-->
<!--	<div class="flex-col activity-1-list" id="my_self">-->
<!--		<div class="flex-item-2 tc">-->
<!--			<img src="<%:=list.photo%>" alt="头像" width="47" height="47" class="img-circle">-->
<!--		</div>-->
<!--		<div class="flex-item-6 pl10">-->
<!--			<p class="pt5"><%:=list.nickname%></p>-->
<!--			<p class="gray6 f12 pt2"><%:=list.datetime%></p>-->
<!--		</div>-->
<!--		<div class="flex-item-4 tr org">-->
<!--			<%:=list.des%>-->
<!--		</div>-->
<!--	</div>-->
<!--    </div>-->
<!--</script>-->
<?php $this->beginBlock('J_Reviews') ?>
var $hand = $('.hand');
var time_count=0;
$hand.click(function(){
$.showLoading();
$.post('/choujiang/apply-new',{v:new Date().getTime(),'id':'<?=$id?>'},function(res){
$.hideLoading();
if(res.status){
time_count++;
rotateFunc(res.angle,res.message);
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

$(".click_coupon").on('click',function(){
console.log($(this).data('content'));
var obj=$(this);
$.showLoading("正在加载");
$.post('<?=\yii\helpers\Url::to('/coupon/ajax-apply',true)?>',{coupon_id:$(this).data('content')},function(data){
$.hideLoading();
if(data.status){
    $.alert('领取成功，请于1月6号使用');
}else{
$.toast(data.message);
}
},'json');
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
	'desc' => '酸奶、水果、饮料、日常用品。来每日惠购就“购”了!',
	'link' => \yii\helpers\Url::to(['/site/index'],true),
	'image' => Yii::$app->request->getHostInfo() . '/assets/images/hongbao_share.jpg',
    'hidden_status'=>'hidden',
]]);
?>
