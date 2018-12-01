<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/11
 * Time: 11:59
 */
$this->title="确认订单";
?>
<style>
    .weui-popup-container{
        z-index: 9999;
    }
</style>
<header class="fx-top bs-bottom whitebg lh44">
	<div class="flex-col tc">
		<a class="flex-item-2" href="/express/index">
			<i class="aui-icon aui-icon-left green f28"></i>
		</a>
		<div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($this->title) ?>
		</div>
		<a class="flex-item-2 refresh_btn" href="javascript:location.reload(true);" >
			<i class="aui-icon aui-icon-refresh green f28"></i>
		</a>
	</div>
</header>
<div class=" pt10 pb50">
    <div class="pt50"></div>
	<?=h5\widgets\Block\Address::widget()?>
	<p class="tit--">选择配送时间</p>
	<?= h5\widgets\Block\Delivery::widget() ?>
	<p class="tit--">商品明细</p>
	<div class=" w">
        <div class="flex-col bdb fb lh150">
            <div class="flex-item-2 tc">编号</div>
            <div class="flex-item-8">名称规格</div>
            <div class="flex-item-2 tc">数量</div>
        </div>
        <?php if($model){?>
        <?php foreach ($model as $key=>$coupon_product){?>
		<div class="flex-col bdb p10 bg-wh">
			<div class="flex-item-2 tc"><?=$key+1?></div>
			<div class="flex-item-8"><?=$coupon_product->product->description->name?></div>
			<div class="flex-item-2 tc">X 1</div>
		</div>
        <?php }?>
        <?php }else{?>
            <p class="tc lh200">当前没有任何商品信息</p>
        <?php }?>
	</div>
    <div class="mt5 p10">
        <h2>备注：</h2>
        <textarea  class="w p10 gray6 bd" name="remark" placeholder="如有特殊需求请填写" maxlength="100" id="remark"></textarea>
    </div>
</div>
<div class="fx-bottom  bdt whitebg p10" style="z-index: 99;">
	<a id="button_submit" href="javascript:;"  class="btn lbtn greenbtn w">
		提交订单
	</a>
</div>
<?php $this->beginBlock('JS_END') ?>
    $("#button_submit").on("click",function(){
    $.showLoading("正在加载");
    var address_id=$(".address_container").attr('data-content');
    var delivery_date=$(".delivery-container").attr('data-date');
    var delivery_time=$(".delivery-container").attr('data-time');
    var customer_coupon_id = <?php echo Yii::$app->request->get('customer_coupon_id');?>
    var remark=$("#remark").val();
    if(!address_id){
    $.alert('请选择收货地址');
    return;
    }
    $.post('/user-coupon/save-order',{address_id:address_id,delivery_date:delivery_date,delivery_time:delivery_time,remark:remark,customer_coupon_id:customer_coupon_id},function(res){
    $.hideLoading();
    if(res.status){
    $.toast('提交成功',function(){
    location.href=res.location;
    });
    }else{
    $.alert(res.message);
    }
    },'json');
    });
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>