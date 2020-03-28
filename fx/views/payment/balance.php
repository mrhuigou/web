<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/11/18
 * Time: 9:19
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title="手机支付密码"
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  mb50">
    <p class="tc fb f14 mt50">输入你的支付密码</p>
    <?php $form = ActiveForm::begin(['id' => 'form-address','fieldConfig' => [
        'template' => "<p>{input}</p>{error}",
        'errorOptions'=>['class'=>'red tc','tag'=>'div']
    ],  ]);?>
<div class="w mt20 mb10 ">
    <div class="sixDigitPassword bc " tabindex="0" style="width: 240px;">
        <i class="" style="width: 39px; border-color: transparent;"><b style="visibility: hidden;"></b></i>
        <i class="" style="width: 39px;"><b style="visibility: hidden;"></b></i>
        <i class="" style="width: 39px;"><b style="visibility: hidden;"></b></i>
        <i class="" style="width: 39px;"><b style="visibility: hidden;"></b></i>
        <i class="" style="width: 39px;"><b style="visibility: hidden;"></b></i>
        <i class="" style="width: 39px;"><b style="visibility: hidden;"></b></i>
        <span style="width: 39px; visibility: hidden; left: 0px;"></span>
    </div>
    <?= $form->field($model, 'password',['inputOptions'=> ["class"=>'ui-input i-text sixDigitPassword','tabindex'=>1,'autocomplete'=>"off",'value'=>'','style'=>'outline: medium none;']])?>
</div>
   <div class="p10">
       <button type="submit" class="btn lbtn w greenbtn mt10" id="subBtn">确认支付</button>
   </div>
    <?php ActiveForm::end(); ?>
</section>
<?php $this->beginBlock('JS_END') ?>
$(".sixDigitPassword").click(function(){
var len=$("input.sixDigitPassword").val().length;
$("div.sixDigitPassword").find("i").removeClass("active").eq(len).addClass("active");
//高亮框
$("div.sixDigitPassword").find("span").css("visibility","visible");
$("input.sixDigitPassword").focus().keyup(function(){
var len=$("input.sixDigitPassword").val().length;
$("div.sixDigitPassword").find("i").removeClass("active").eq(len).addClass("active");
(len>5) ? $("div.sixDigitPassword").find("span").animate({"left":5*40},170) : $("div.sixDigitPassword").find("span").stop().animate({"left":len*40},170);
for(e=0;e<len;e++){
$("div.sixDigitPassword").find("i").eq(e).find("b").css("visibility","visible");
}
for(e=len;e<7;e++){
$("div.sixDigitPassword").find("i").eq(e).find("b").css("visibility","hidden");
}
}).blur(function(){
var len=$("input.sixDigitPassword").val().length;
$("div.sixDigitPassword").find("i").removeClass("active").eq(len).removeClass("active");
$("div.sixDigitPassword").find("span").css("visibility","hidden");
});
})
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
$this->registerCssFile("/assets/stylesheet/plugin/sixDigitPassword.css",['depends'=>[\h5\assets\AppAsset::className()]]);
?>
<?php $this->beginBlock('JS_END') ?>
$("#subBtn").on('click',function(){
$.showLoading("正在加载");
setTimeout("$.hideLoading();",1000);
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
