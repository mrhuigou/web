<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/16
 * Time: 14:13
 */
use \yii\helpers\Html;
?>
<div id="tlp_content">
</div>
<script id="tpl1" type="text/html">
	<dl class="mt5">
		<dt class="p5 whitebg bdt">相关推荐商品</dt>
		<dd>
				<div class="row">
					<% for(var i=from; i<=to; i++) {%>
					<div class="col-3 p5 ">
						<div class="whitebg">
                            <div class="item-photo">
                                <a href="<%:=list[i].url%>" class="db">
                                    <img src="<%:=list[i].image%>"  class="w">
                                </a>
                                <% if(list[i].stock <=0){ %> <em class="item-tip iconfont ">&#xe696;</em> <%}%>
                            </div>
						<div class="pt5">
							<a href="<%:=list[i].url%>" class="f14 db lh200 mxh35 tc">
								<%:=list[i].name%>
							</a>
							<p class="mt5 mb5 tc clearfix">
								<span class="red vm fl">￥<%:=list[i].cur_price%></span>
								<a class="redbg db fr white " href="<%:=list[i].url%>" style="font-size: 25px; height: 25px; width: 25px; line-height: 20px; border-radius: 100%; text-align: center; vertical-align: middle;">+</a>
							</p>
						</div>
						</div>
					</div>
					<% } %>
				</div>
		</dd>
	</dl>
</script>
<?php $this->beginBlock('JS_END') ?>
var wx_xcx = <?php echo Yii::$app->session->get('source_from_agent_wx_xcx') ? 1:0  ?>;
    var source = getSourceParms();
	var tpl1 = $('#tpl1').html();
	var item_id='<?=$item_id?>'
	$.getJSON('<?php echo Yii::$app->params["API_URL"]?>/mall/v1/product/index?callback=?&'+source+'&wx_xcx='+wx_xcx,{
	item_id:item_id}, function(result){
	if(result.data.length>0){
     var mod=result.data.length%3;
	if(result.data.length-mod>0){
	var html= template(tpl1, {list:result.data,from:0,to:result.data.length-mod-1});
	$("#tlp_content").html(html);
}
	}
	})
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>