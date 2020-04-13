<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/3/1
 * Time: 15:09
 */
?>
<style>
    .weui_mask,.weui_dialog{
        z-index: 9999;
    }
    .weui_dialog{
        background-color: transparent;
        padding: 0 0;
    }
    .weui_dialog_hd,.weui_dialog_ft{
        display: none;
    }
    .weui_dialog_bd{
        padding: 0px 0px;
    }
    .weui_dialog{
        top:20%;
    }
    .dialog-close{
        border: 2px solid #fff;
        border-radius: 50%;
        color: #fff;
        font-family: iconfont;
        font-size: 18px;
        font-style: normal;
        font-weight: bold;
        height: 40px;
        line-height: 33px;
        position: absolute;
        right: 0;
        top: 0;
        width: 40px;
    }
</style>
<?php $this->beginBlock('JS_START') ?>
<?php if($type){?>
$.modal({
title: "",
<!--text: '<img src="/images/subcription_pic_mrhuigou1.png" class="w">',-->
text: '<div style="position: relative;"><img src="<?= 'https://img1.mrhuigou.com/'.$share_logo?>" class="w"></div><div style="position: absolute; top: 16.6rem;right: 3.2rem"><img src="<?= $ticket_code;?>" class="" style="width: 7.5rem;"></div>',
buttons: []
});
<?php }else{?>
    <?php $status = 1;//添加默认值不弹送28优惠券的?>
    <?php if(!$status){?>
    $.modal({
    title: "长按识别关注",
    text: '<img src="/images/sub_rednew.jpg" class="w"><i class="iconfont dialog-close weui_btn_dialog">&#xe612;</i>',
    buttons: [
    { text: "<span>逛一逛，稍后在说</span>", className: "default"},
    ]
    });
        <?php }else{?>
        $.modal({
        title: "",
<!--        text: '<img src="/images/subcription_pic_mrhuigou1.png" class="w">',-->
        text: '<div class="layer-status"><div style="position: relative;"><img src="<?= 'https://img1.mrhuigou.com/'.$share_logo?>" class="w"></div><div style="position: absolute; top: 16.6rem;right: 3.2rem"><img src="<?= $ticket_code;?>" class="" style="width: 7.5rem;"></div><a class="layer-close iconfont" href="javascript:;">&#xe612;</a></div>',
        buttons: []
        });
        <?php }?>
<?php }?>

$(".layer-close").click(function(){
    $('.weui_mask').remove();//去掉遮罩层
    $('.layer-status').hide();
})
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_START'],\yii\web\View::POS_END);
?>
