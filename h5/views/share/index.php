<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/27
 * Time: 9:02
 */
$this->title = '分享有礼';
?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="/">
            <i class="aui-icon aui-icon-home green f28"></i>
        </a>
        <div class="flex-item-8 f16">
			<?=\yii\helpers\Html::encode($this->title)?>
        </div>
        <a class="flex-item-2 share-guide" href="javascript:;" >
            <i class="iconfont green f28">&#xe644;</i>
        </a>
    </div>
</header>

<div class="bg-wh mt50">
    <!-- banner -->
    <img src="/assets/images/share/new-banner.jpg" class="w">
    <!-- 福利券 -->
    <div class="pt10 pb30 whitebg">
        <p class="f24 fb org mt5 mb10 tc">新会员专享福利券</p>
        <?php if($coupons){?>
            <div class="swiper-container pt10 pb10 shareQuan" id="shareQuan">
                <div class="swiper-wrapper">
                    <?php foreach($coupons as $value){?>
                        <div class="swiper-slide bc" style="    border-bottom: 3px solid #f34872;">
                            <div class="pink mb5 clearfix">
                                <?php if($value->shipping){?>
                                    <span class="fr m"><i class="f16">￥</i><i class="f24 fb vm-2">8</i></span>
                                <?php }else{?>
                                    <span class="fr m">
							<?php if($value->type=='F'){?>
                                <i class="f16">￥</i><i class="f24 fb vm-2"><?=floatval($value->discount)?></i>
                            <?php }else{?>
                                <i class="f16">折</i><i class="f24 fb vm-2"><?=$value->getRealDiscount()?></i>
                            <?php }?>
						</span>
                                <?php }?>
                                <span class="f16 oh"><?=$value->type=='F'?"现金券":"折扣券"?></span>
                            </div>
                            <span class="gray6"><?=$value->description?></span>
                            <i class="iconfont">&#xe698;</i>
                        </div>
                    <?php }?>
                </div>


            </div>
        <?php }?>
        <div class="tc pb20">
            <?php if(Yii::$app->user->identity->getId()){
                if($auth=\api\models\V1\CustomerAuthentication::findOne(['customer_id'=>Yii::$app->user->identity->getId(),'provider'=>'WeiXin'])) {
                    if ($auth->status) { ?>
                        <!-- 已关注公众号 -->
                        <p class="f16 fb mt10 mb15">分享给新朋友 还有更多惊喜</p>
                        <a href="javascript:void(0)" class="btn btn-l btn-pink btn-rounded fb f20 pw40 share-guide">立即分享</a>
                    <?php  } else {?>
                        <!-- 未关注公众号 -->
                        <p class="f16 fb mt10 mb5">长按识别二维码 关注公众号领取</p>
                        <img src="https://m.mrhuigou.com/assets/images/wx.jpg" width="200">
                    <?php   }
                }
                ?>
            <?php }?>
        </div>
        <div class="ad-container Items" id="ad-container"></div>
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
                <h2>爆品专区</h2>
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
        <?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){ ?>
            <div class="tc p10">
			<span class="share-weixin">
				<img src="/assets/images/wx-zhqd.png" width="130" height="130px">
			</span>
                <p class="mt10">关注公众号，了解更多...</p>
            </div>

        <?php }?>

    </div>

</div>
<?= h5\widgets\MainMenu::widget(); ?>
	<div class="f0bg tc pt15 pb20">
		<p class="gray6 mt5">客服电话：0532-55729957</p>
	</div>
<?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $share_image = Yii::$app->request->getHostInfo().'/assets/images/zhqd/logo_300x300.jpeg';
}else{
    $share_image = Yii::$app->request->getHostInfo().'/assets/images/logo_300x300.png';
}?>
<?=\h5\widgets\Tools\Share::widget([
	'data'=>[
		'title' =>'终于等到你，小鲜肉们接收大礼包!',
		'desc' => '新鲜酸奶全场半价，上午下单下午到。',
		'link' => \yii\helpers\Url::to(['/share/subscription', 'share_user_id' => Yii::$app->user->getId(),'redirect'=>'/site/index'], true),
		'image' => $share_image
	]
])?>
<script>
<?php $this->beginBlock("JS") ?>
//滑动
var wx_xcx = <?php echo Yii::$app->session->get('source_from_agent_wx_xcx') ? 1:0  ?>;
var source = getSourceParms();
var selectItems = new Swiper('#shareQuan', {
    slidesPerView: "auto"
});
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
</script>
<?php
$this->registerJs($this->blocks['JS'], \yii\web\View::POS_END);
?>

