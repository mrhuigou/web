<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='手机验证';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  mb50">
    <?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w fx-convert-tri',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]);?>
    <?= $form->field($model, 'telephone',["inputOptions"=>['placeholder'=>'请输入手机号','class' => 'input-text w telephone fx-convert-tri']]) ?>
    <?= $form->field($model, 'realname',["inputOptions"=>['placeholder'=>'请输入真实姓名']])?>
    <?= $form->field($model, 'verifyCode',[
        'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div><button  type='button' class=\"appbtn graybtn w-per35 fr\" name='send-vcode' id='send-vcode'>获取验证码</button></div>{error}",
        "inputOptions"=>["maxlength"=>"6","autocomplete"=>"off",'placeholder'=>'请输入语音验证码'],
    ]) ?>
    <p class="red lh150">家润慧生活通过语音播报验证码,请你接听来自0532/400/12590开头的来电.</p>
    <?= Html::submitButton('手机验证', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'realname-button']) ?>
    <?php ActiveForm::end(); ?>
</section>

