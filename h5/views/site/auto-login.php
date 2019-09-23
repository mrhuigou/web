<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/19
 * Time: 10:40
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title="绑定手机登录";
?>
<div class="tc p20 f14">
	<img src="<?=\common\component\image\Image::resize($authentication_model->photo_url,120,120)?>" width="120" height="120" class="img-circle mt30">
	<h3 class="pt5 f16"><?=$authentication_model->display_name?></h3>
	<p class="gray9 pt15 pb5 tit--">绑定手机登录</p>
    <div class="tl">
	<?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
		'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
		'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
		'errorOptions'=>['class'=>'error db']
	],  ]);?>
	<?= $form->field($model, 'telephone',["inputOptions"=>['placeholder'=>'请输入手机号','class' => 'input-text w telephone']]) ?>
	<?= $form->field($model, 'verifyCode',[
		'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}</div><a href=\"javascript:;\" class=\"btn lbtn graybtn w-per40  f12  \"  id='send-vcode'>获取验证码</a></div>{error}",
		"inputOptions"=>["maxlength"=>"6","autocomplete"=>"off",'placeholder'=>'验证码'],
	]) ?>
    <p class="gray9 pt15 pb5">家润慧生活通过语音播报验证码,请你接听来自0532/400/12590开头的来电.</p>
	<?= Html::submitButton('确认登录', ['class' => 'btn lbtn greenbtn w mt10', 'name' => 'signup-button']) ?>
	<?php ActiveForm::end(); ?>
    </div>
</div>

