<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='收货地址';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  pb50">
    <div id="address_list" class="pb50">
    <?php if($address){ ?>
       <?php foreach($address as $value){ ?>
            <div class="flex-col flex-center store-item bdb  whitebg p5 item-address <?=$value->default?"red":""?>">
                <label class="label-checkbox item-content flex-item-1 flex-row flex-middle flex-center">
                    <input type="radio" value="<?=$value->address_id?>" name="address_id"  class="item" <?=$value->default?'checked':""?>>
                    <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                </label>
            <div class="flex-item-9 ">
                <p><span class="fb"><?=$value->firstname?></span><em class="ml10"><?=$value->telephone?></em></p>
                <p><?=$value->zone?$value->zone->name:""?>-<?=$value->citys?$value->citys->name:""?>-<?=$value->district?$value->district->name:""?></p>
                <p>
	                <?=$value->address_1?>
                </p>
            </div>
            <div class="flex-item-2 flex-row flex-middle flex-center">
                <?php $in_range = Yii::$app->request->get('in_range',1);?>
                <a href="<?=\yii\helpers\Url::to(['/address/update','id'=>$value->address_id,'redirect'=>Yii::$app->request->getAbsoluteUrl() ,'range'=>$in_range == 1 ? '' : 'all_range'])?>" class="iconfont gray9 ">&#xe615;</a>
            </div>
            </div>
        <?php } ?>
    <?php }else{ ?>
        <p class="tc p20"> 您还没有添加过收货地址！</p>
    <?php } ?>
    </div>
</section>
<div class="fx-bottom  bdt whitebg p10 w tc ">
    <?php  if(\Yii::$app->request->get("redirect")){?>
        <a class="btn mbtn greenbtn mr5 " id="confirm_address" href="javascript:;">确认选择</a>
    <?php } ?>
    <a  class="btn mbtn bluebtn"   href="<?=\yii\helpers\Url::to(['/address/create','redirect'=>Yii::$app->request->getAbsoluteUrl()],true)?>" >添加新地址</a>
</div>
<?php $this->beginBlock('JS_END') ?>
$("#address_list  .item-address").click(function(){
$(this).addClass('red').siblings().removeClass('red');
$(this).find('input:radio').attr('checked',true);
});
$("#confirm_address").on("click",function(){
if($("input[type='radio']:checked").val()){
$.post('/checkout/select-address',{address_id:$("input[type='radio']:checked").val()},function(data){
if(data.result){
<?php if($redirect=Yii::$app->request->get('redirect')){?>
location.href="<?=$redirect?>";
<?php }else{?>
location.href="/checkout/index";
<?php }?>
}
},'json');
}else{
alert("请选择一个地址作为收货地址");
}
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
