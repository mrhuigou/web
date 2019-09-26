<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
$this->title = "忘记密码";
?>
<div  class="w990 bc pt20" style="min-height:500px;">
    <div class="steps">
        <ol>
            <li class="active"><i>1</i><span>选择方式</span></li>
            <li><i>2</i><span>重置密码</span></li>
            <li><i>3</i><span>找回成功</span></li>
        </ol>
    </div>
    <div class="reg-box bc ">
        <div class="mt15 bc w400 ">
            <ul class="tag-tit w400 tc clearfix mb10" style="display: block;">
                <li class=" w200 <?=Yii::$app->request->get('type')?"":'current'?>" style="padding: 0;"><a href="<?=\yii\helpers\Url::to(['/site/request-password-reset'])?>">通过手机找回</a></li>
                <li  class="w200 <?=Yii::$app->request->get('type')?"current":''?>" style="padding: 0;"><a href="<?=\yii\helpers\Url::to(['/site/request-password-reset','type'=>'email'])?>">通过邮箱找回</a></li>
            </ul>
        </div>
        <div class="mt15 bc w400 ">
            <?php if(!Yii::$app->request->get('type')){ ?>
                <?php $form = ActiveForm::begin(['fieldConfig'=>['options'=>['class'=>'p5 clearfix'],'labelOptions'=>['class'=>'db fb w tl '],'errorOptions'=>['tag'=>'p'],'inputOptions' =>['class' => 'input linput w']]]); ?>
                <?= $form->field($model, 'telephone');?>
                <?= $form->field($model, 'verifyCode',['template' => '{label}{input}{error}<div><button type="button"  id="send-vcode" class="btn w lbtn db " style="height: 41px;">获取验证码</button></div>',])->widget(Captcha::className(), [ "options"=>['class' => 'input linput db placeholder fl verifycode','style'=>'width:50%'],
                    "imageOptions"=>["style"=>"width:45%;height:41px;",'class'=>'cp'],
                    'template' => '{input}{image}',
                ]) ?>
                <p class="red lh150 p5">每日惠购通过语音播报验证码,请你接听来自0532/400/12590开头的来电.</p>
                <?= $form->field($model, 'checkcode',['inputOptions' =>['class' => 'input linput db placeholder w checkcode'],'template' => "{label}{input}\n{hint}\n{error}"]) ?>
                <div class="p5 tc"><button type="submit" class="btn lbtn greenbtn w">下一步</button></div>
                <?php ActiveForm::end(); ?>
            <?php }else{ ?>
                <?php $form = ActiveForm::begin(['fieldConfig'=>['options'=>['class'=>'p5 clearfix'],'labelOptions'=>['class'=>'db fb w tl '],'errorOptions'=>['tag'=>'p'],'inputOptions' =>['class' => 'input linput w']]]); ?>
                <?= $form->field($model, 'email');?>
                <?= $form->field($model, 'verifyCode',['template' => '{label}{input}{error}',])->widget(Captcha::className(), [ "options"=>['class' => 'input linput db placeholder fl verifycode','style'=>'width:50%'],
                    "imageOptions"=>["style"=>"width:45%;height:41px;",'class'=>'cp'],
                    'template' => '{input}{image}',
                ]) ?>
                <div class="p5 tc"><button type="submit" class="btn lbtn greenbtn w">下一步</button></div>
                <?php ActiveForm::end(); ?>
            <?php } ?>
        </div>
    </div>
</div>
<div id="footer"></div>
<?php $this->beginBlock("JS_Block")?>
$("#send-vcode").click(function(){
var _this=$(this);
var telephone = $("#passwordresetrequestform-telephone");
var verifycode=$("#passwordresetrequestform-verifycode");
$.post('/site/sendcode',{'telephone':telephone.val(),'verifycode':verifycode.val()},function(data){
if(data.status==0){
$.each(data.message,function(name,value){
if($.inArray(name, ['telephone','verifycode'])>=0){
var _parent=$("#passwordresetrequestform-"+name).parent(".required");
_parent.removeClass("has-success").addClass("has-error");
_parent.find(".help-block-error").html(value);
}else{
alert(value);
}
});
$("#passwordresetrequestform-verifycode-image").trigger('click');
}else{
time(_this);
}
},'json');
});
<?php $this->endBlock()?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_READY);
?>
