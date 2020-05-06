<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/11/1
 * Time: 17:37
 */
?>
<!--送至-->
<div class="whitebg bdt bdb pt5 pb5 mb5">
	<div class="flex-col f12" id="addressTri">
		<div class="flex-item-2 tc gray9">
			送至
		</div>
		<div class="flex-item-9">
			<p class="checkout_address ellipsis"><?=$model?$model->address_1:"青岛市内四区"?></p>
			<p class="red pt5">现在完成下单，<?=$deliver_msg?></p>
		</div>
		<div class="flex-item-1 lh44">
			<i class="iconfont f12 grayc fr mr5">&#xe60b;</i>
		</div>
	</div>
</div>
<script id="addressPop" type="text/html">
	<div class="w bdb tc p10 lh200 f16 clearfix">
		<a class="fl" href="javascript:;" id="close_pop">
			<i class="aui-icon aui-icon-left green f28"></i>
		</a>
		选择配送地址
	</div>
	<div class="p10 which addresslist pa-t graybg" style="top: 50px; bottom: 50px; overflow-y: scroll;">
	<% for(var i=from; i<=to; i++) {%>
	<a class="item mt5 db w <%:=list[i].default%>" data-id="<%:=list[i].address_id%>" href="javascript:;">
		<p class="item-address"><%:=list[i].address%></p><p><%:=list[i].username%>  <%:=list[i].telephone%></p><i class="iconfont cur-item"></i></a>
	<% } %>
</div>
<div class="fx-bottom p5 tc graybg bdt">
	<a class="btn mbtn w greenbtn" href="<?=\yii\helpers\Url::to(['/address/create','redirect'=>Yii::$app->request->getAbsoluteUrl()])?>">创建新地址</a>
</div>
</script>
<?php $this->beginBlock('JS_END') ?>
//选择地址
$("#addressTri").click(function(){
$.showIndicator();
$.post('<?= \yii\helpers\Url::to('/address/my-address', true) ?>',function(data){
$.hideIndicator();
if(!data){
alert("您还没有添加地址呢！");
}
var addressPop=$('#addressPop').html();
var html= template(addressPop, {list:data,from:0,to:data.length-1});
layer.open({
type: 1,
title: "选择配送地址",
closeBtn: 1,
area: 'auto',
style: 'position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px;',
shadeClose: true,
content:html
});
},'json');
});
$("body").on("click",'.addresslist .item',function(){
var address_id=$(this).attr('data-id');
var confirm_address=$(this).find(".item-address").html();
$.showIndicator();
$(this).addClass("cur").siblings().removeClass("cur");
$.post('<?= \yii\helpers\Url::to('/checkout/select-address', true) ?>',{'address_id':address_id},function(data){
$.hideIndicator();
if(data.result){
$(".checkout_address").html(confirm_address);
layer.closeAll();
}
},'json');
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
