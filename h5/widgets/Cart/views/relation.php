<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/9/25
 * Time: 21:49
 */
?>
<!--<div class="tc f16  w green p5 lh200 pt10"> // // 买 了 又 看 // // </div>-->
<div>
    <a class="btn mbtn tc w" style="font-size: 17px; border: 1px solid #404040; background-color: #fff;color: red;" href="/site/index">点 击 继 续 购 物</a>
</div>

<!--<dl class="mt10">-->
<dl class="mt5" id="baoyou" style="display: none">
    <dt class=" f14 whitebg org fb tc bdt bdb">
    <div class="tit1 orgtit1 clearfix">
        <h2 class="fl f14">任选一件整单包邮</h2>
        <a class="fr  org lh200 fn" href="https://m.mrhuigou.com/topic/detail?code=EPP001514">更多&gt;&gt;</a>
    </div>
    </dt>
    <dd>
        <div id="tlp_content_3">
            <div class="loading w tc lh200 p10">正在加载中...</div>
        </div>
    </dd>
</dl>
<dl class="mt10">
	<dt class="whitebg blue fb tc bdt bdb">
	<div class="tit1 bluetit1 clearfix">
		<h2 class="fl f14">大家都在买</h2>
		<a class="fr  blue lh200 fn" href="<?=\yii\helpers\Url::to(['page/3538.html'])?>">更多&gt;&gt;</a>
	</div>
	</dt>
	<dd>
		<div id="tlp_content_2">
			<div class="loading w tc lh200 p10">正在加载中...</div>
		</div>
	</dd>
</dl>
<script id="tpl" type="text/html">
			<div class="swiper-container brandList  whitebg" id="<%:=index%>">
				<div class="swiper-wrapper">
					<% for(var i=from; i<=to; i++) {%>
					<div class="swiper-slide">
                        <div class="item-photo">
                        <a href="<%:=list[i].url%>" class="db">
							<img src="<%:=list[i].image%>"  class="w">
						</a>
                        <% if(list[i].stock <=0){ %> <em class="item-tip iconfont">&#xe696;</em> <%}%>
                        </div>
                        <div class="pt5">
							<a href="<%:=list[i].url%>" class="f14 db lh200 mxh35">
								<%:=list[i].name%>
							</a>
							<p class="mt5 mb5 tc clearfix">
								<span class="red vm fl">￥<%:=list[i].cur_price%></span>
								<a class="redbg db fr white " href="<%:=list[i].url%>" style="font-size: 25px; height: 25px; width: 25px; line-height: 20px; border-radius: 100%; text-align: center; vertical-align: middle;">+</a>
							</p>
						</div>
					</div>
					<% } %>
				</div>
			</div>
</script>
<?php $this->beginBlock('JS_END') ?>
var source = getSourceParms();
var wx_xcx = <?php echo Yii::$app->session->get('source_from_agent_wx_xcx') ? 1:0  ?>;
$.getJSON('<?php echo Yii::$app->params['API_URL']?>/mall/v1/ad/product?callback=?&'+source,{code:'H5-0F-AD',wx_xcx:wx_xcx}, function(result){
var html_content= template($('#tpl').html(), {list:result.data,from:0,to:result.data.length-1,index:'all_sider'});
$("#tlp_content_2").html(html_content);
//爆款滑动
var brandList_2 = new Swiper('#all_sider', {
slidesPerView: "auto"
});
});
$.getJSON('<?php echo Yii::$app->params['API_URL']?>/mall/v1/promotion/subject?callback=?&'+source,{subject:'baoyou',wx_xcx:wx_xcx}, function(result){
var html_content= template($('#tpl').html(), {list:result.data,from:0,to:result.data.length-1,index:'qiuyou_sider'});
$("#tlp_content_3").html(html_content);
if(result.data.length >0){
$("#baoyou").show();
}
//爆款滑动
var brandList_3 = new Swiper('#qiuyou_sider', {
slidesPerView: "auto"
});
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
