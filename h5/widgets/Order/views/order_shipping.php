<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/9/25
 * Time: 21:49
 */
?>
<div class="tc f16  w green p5 lh200 pt10"> // // 买 了 又 看 // // </div>
<div class="w bc" id="tlp_content">
<div class="loading tc w">正在加载中...</div>
</div>
<script id="tpl" type="text/html">
	<div class="flex-col" >
		<% for(var i=from; i<=to; i++) {%>
			<a class="flex-item-6 p5" href="<%:=list[i].url%>">
				<img src="<%:=list[i].image%>" class="w">
			</a>
		<% } %>
	</div>
</script>
<?php $this->beginBlock('JS_END') ?>
var source = getSourceParms();
var wx_xcx = <?php echo Yii::$app->session->get('source_from_agent_wx_xcx') ? 1:0  ?>;
$.getJSON('<?php echo Yii::$app->params['API_URL']?>/mall/v1/ad/index?callback=?&'+source,{code:'H5-0F-SLIDE',wx_xcx:wx_xcx}, function(result){
var html_content= template($('#tpl').html(), {list:result.data,from:0,to:result.data.length-1});
$("#tlp_content").html(html_content);
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
