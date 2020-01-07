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
text: '<img src="/images/subcription_pic_mrhuigou.png" class="w">',
<!--text: '<img src="--><?//= $share_logo?><!--" class="w">',-->
buttons: []
});
<?php }else{?>
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
        text: '<img src="/images/subcription_pic_mrhuigou.png" class="w">',
<!--        text: '<img src="--><?//= $share_logo?><!--" class="w">',-->
        buttons: []
        });
        <?php }?>
<?php }?>
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_START'],\yii\web\View::POS_END);
?>
