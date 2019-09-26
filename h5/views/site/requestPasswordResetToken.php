<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = '找回帐户密码';
$this->params['breadcrumbs'][] = $this->title;
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
</header>
<section class="login-bodyer">
    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]);?>
    <?= $form->field($model, 'telephone',["inputOptions"=>['placeholder'=>'请输入手机号','class' => 'input-text w telephone']]) ?>
    <?= $form->field($model, 'verifyCode',[
        'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del  fr\" style=\"display:none;\"></a></p></div><button  type='button' class=\"appbtn graybtn w-per35 fr\" name='send-vcode' id='send-vcode'>获取验证码</button></div>{error}",
        "inputOptions"=>["maxlength"=>"6","autocomplete"=>"off",'placeholder'=>'请输入验证码'],
    ]) ?>
    <p class="red lh150">每日惠购通过语音播报验证码,请你接听来自0532/400/12590开头的来电.</p>
    <?= Html::submitButton('下一步', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'Send']) ?>
    <?php ActiveForm::end(); ?>
</section>


