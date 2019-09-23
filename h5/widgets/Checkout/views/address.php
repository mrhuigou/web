<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/5/12
 * Time: 10:29
 */
?>
<?=$input?>
<div class="colorbar"></div>
<div class="p10 db  whitebg f14  flex-col"  id="addressTri" >
    <?php if($address) { ?>
        <div class="flex-item-10 select_address">
        <p><em class="confirm-username"><?=$address->firstname?></em><em class="confirm-tel ml10"><?=$address->telephone?></em></p>
        <p class="confirm-zone"><?=$address->citys?$address->citys->name:''?>-<?=$address->district?$address->district->name:""?></p>
        <p class="confirm-address"> <?=$address->address_1?> </p>
        </div>
        <div class="flex-item-2 tr pt20 green">
            修改<i class="iconfont f14 ">&#xe60b;</i>
        </div>
    <?php }else{?>
        <div class="select_address">
        <a class="db p20  rarrow whitebg f14 tc" href="javascript:;"><span class="iconfont fb">&#xe60c;</span>创建您的收货地址 </a>
        </div>
    <?php } ?>
</div>
<div class="colorbar "></div>

<!--地址弹层-->
<script id="addressPop" type="text/html">

    <div class="w bdb tc lh44 bs-b clearfix pr">
        <a class="pa-tl" href="javascript:;" id="close_pop">
            <i class="aui-icon aui-icon-left green f28"></i>
        </a>
        <span class="f16">选择配送地址</span>
<!--        <a class="pa-tr pr5 cp gray6" href="--><?php //=\yii\helpers\Url::to(['/address/index','redirect'=>Yii::$app->request->getAbsoluteUrl(),'in_range'=>1])?><!--">编辑</a>-->
    </div>
    <div class="line-a flex-col w flex-middle red mb5">
        <div class="flex-item-12">
            因系统升级，<span class="fb">暂停黄岛区配送！</span>
            给您带来的不便，我们深感抱歉！
        </div>

    </div>
    <div class="p10 which addresslist pa-t graybg" style="top: 116px; bottom: 50px; overflow-y: scroll;">
        <% for(var i=from; i<=to; i++) {%>
        <a class="item db w mt5 <%:=list[i].default%>" data-id="<%:=list[i].address_id%>" href="javascript:;">
           <div><p><em class="item-username"><%:=list[i].username%></em><em class="ml10 item-tel"><%:=list[i].telephone%></em></p>
            <p class="item-zone"><%:=list[i].city%>-<%:=list[i].district%></p>
            <p class="item-address"><%:=list[i].address%></p>
           </div>
            <i class="iconfont cur-item"></i></a>
        <% } %>
    </div>
    <div class="fx-bottom p5 tc graybg bdt">
        <a class="btn mbtn w greenbtn" href="<?=\yii\helpers\Url::to(['/address/create','redirect'=>Yii::$app->request->getAbsoluteUrl()])?>">创建新地址</a>
    </div>
</script>

<?php $this->beginBlock('JS_END') ?>
    var post_flag = true;
    //选择地址
    $("#addressTri").click(function(){
    $.showLoading("正在加载");
    $.post('<?= \yii\helpers\Url::to(['/address/my-address','in_range'=>1], true) ?>',function(data){
    $.hideLoading();
    if(!data){
    location.href='<?=\yii\helpers\Url::to(['/address/create','redirect'=>Yii::$app->request->getAbsoluteUrl()])?>'
    }
    var addressPop=$('#addressPop').html();
    var html= template(addressPop, {list:data,from:0,to:data.length-1});
    layer.open({
    type: 1,
    area: 'auto',
    style: 'position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px;',
    content:html
    });
    },'json');
    });
    $("body").on("click",'.addresslist .item',function(){
    var address_id=$(this).attr('data-id');
    var confirm_address=$(this).find('div').html();
    $.showLoading("正在加载");
    $(this).addClass("cur").siblings().removeClass("cur");
    $.post('<?= \yii\helpers\Url::to('/checkout/select-address', true) ?>',{'address_id':address_id},function(data){
    $.hideLoading();
    if(data.result){
    $("#checkoutform-address_id").val(address_id);
    $(".select_address").html(confirm_address);
    $(".error").removeClass('db');
    $(".error").hide();
        post_flag = true;
    layer.closeAll();
    }
    },'json');
    });
    $("body").on("click","#close_pop",function(){
    layer.closeAll();
    });
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>