<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
$this->title = '网站管理系统';
?>
<!-- BEGIN LOGO -->
<div class="logo">
    <img src="http://img1.mrhuigou.com/group1/M00/06/E6/wKgB7l7sLsiAZnE_AAAbvrBPGTg113.png" alt=""/>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <?php $form = ActiveForm::begin(['id' => 'login-form','fieldConfig' => [
        'labelOptions'=>['class'=>'control-label visible-ie8 visible-ie9'],
         'inputOptions' => ['class' => 'form-control placeholder-no-fix',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'help-block']
    ]]); ?>
    <h3 class="form-title"><?= Html::encode($this->title) ?></h3>
    <?= $form->field($model, 'username',["inputOptions"=>['placeholder'=>'用户名'],'template'=>"{label}<div class=\"input-icon\"><i class=\"fa fa-user\"></i>{input}</div><span  class=\"help-block\">{error}</span>"]) ?>
    <?= $form->field($model, 'password',["inputOptions"=>['placeholder'=>'密码'],'template'=>"{label}<div class=\"input-icon\"><i class=\"fa fa-lock\"></i>{input}</div><span  class=\"help-block\">{error}</span>"])->passwordInput() ?>
    <?= $form->field($model, 'verifyCode',['template'=>"{label}{input}<span  class=\"help-block\">{error}</span>"])->widget(Captcha::className(), [
      'template'=>"<div class=\"row\"><div class=\"col-xs-6\"><div class=\"input-icon\"><i class=\"fa fa-barcode\"></i>{input}</div></div><div class=\"col-xs-6\">{image}</div></div>",
        "options"=>['placeholder'=>'验证码','class' => 'form-control placeholder-no-fix',"autocomplete"=>"off"],
    ]) ?>
    <div class="form-actions">
        <label class="checkbox">
            <?= $form->field($model, 'rememberMe')->checkbox() ?></label>
        <button type="submit" class="btn green pull-right">
            登陆 <i class="m-icon-swapright m-icon-white"></i>
        </button>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<!-- END LOGIN -->