<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/12
 * Time: 10:33
 */
?>
<div class="colorbar"></div>
<a class="address_container db w bg-wh p10 open-popup" href="javascript:;"  data-target="#address_list" data-content="<?=$model?$model->address_id:0?>">
<?php if($model){?>
    <div class="flex-col" >
	<div class="flex-item-10 select_address" >
		<p><em class="confirm-username"><?=$model->firstname?></em><em class="confirm-tel ml10"><?=$model->telephone?></em></p>
		<p class="confirm-zone"><?=$model->citys?$model->citys->name:''?>-<?=$model->district?$model->district->name:""?></p>
		<p class="confirm-address"> <?=$model->address_1?> </p>
	</div>
	<div class="flex-item-2 tr pt20 green">
		修改<i class="iconfont f14 ">&#xe60b;</i>
	</div>
    </div>
<?php }else{?>
	<div class="select_address">
		<p class="db p20  rarrow whitebg f14 tc" ><span class="iconfont fb">&#xe60c;</span>创建您的收货地址 </p>
	</div>
<?php }?>
</a>
<div class="colorbar "></div>
<div id="address_list" class="weui-popup-container">
    <div class="weui-popup-overlay"></div>
    <div class="weui-popup-modal">
        <div class="w bdb tc lh44 bg-wh bdb">
            <a class="pa-tl close-popup" href="javascript:;">
                <i class="aui-icon aui-icon-left green f28"></i>
            </a>
            <span class="f16">选择配送地址</span>
            <a class="pa-tr pr5 cp gray6" href="<?=\yii\helpers\Url::to(['/address/index','redirect'=>Yii::$app->request->getAbsoluteUrl(),'in_range'=>0])?>">编辑</a>
        </div>
        <div class="which addresslist p10" style="bottom: 55px;top: 45px;">
        <?php foreach ($list_model as $value){?>
            <a class="item db w mt5 <?=$value->address_id==($model?$model->address_id:0)?'cur':''?>" data-id="<?=$value->address_id?>" href="javascript:;">
                <div><p><em class="item-username"><?=$value->firstname?></em><em class="ml10 item-tel"><?=$value->telephone?></em></p>
                    <p class="item-zone"><?=$value->citys?$value->citys->name:''?>-<?=$value->district?$value->district->name:""?></p>
                    <p class="item-address"><?=$value->address_1?></p>
                </div>
                <i class="iconfont cur-item"></i>
            </a>
        <?php }?>
        </div>
        <div class="fx-bottom p5 tc bg-wh bdt" style="z-index: 9999;">
            <a class="btn lbtn w greenbtn" href="<?=\yii\helpers\Url::to(['/address/create','redirect'=>Yii::$app->request->getAbsoluteUrl(),'range'=>'all_range'])?>">创建新地址</a>
        </div>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>
$("body").on("click",'.addresslist .item',function(){
var address_id=$(this).attr('data-id');
$.showLoading("正在加载");
$(this).addClass("cur").siblings().removeClass("cur");
$.post('<?= \yii\helpers\Url::to(['/express/select-address','range'=>'all_range'], true) ?>',{'address_id':address_id},function(data){
$.hideLoading();
if(data.result){
$(".address_container").html(data.html);
$(".address_container").attr('data-content',address_id);
$.closePopup();
}else{
$(".address_container").attr('data-content',0);
$.alert(data.message);
}
},'json');
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);

?>
