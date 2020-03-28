<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

$this->title = '设置新密码';
$this->params['breadcrumbs'][] = $this->title;
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
</header>

<section class="login-bodyer">
    <?php $form = ActiveForm::begin(['id' => 'reset-password-form','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]);?>
    <?= $form->field($model, 'password',["inputOptions"=>['placeholder'=>'设置你的新密码'], 'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fl\" style=\"display:none;\"></a><a href=\"javascript:void(0);\" class=\"pwd-btn right fl \"><span class=\"bg_l\"></span><span class=\"bg_r\"></span><i>隐藏</i></a></p></div>{error}"])->passwordInput() ?>
    <?= $form->field($model, 'verifyPassword',["inputOptions"=>['placeholder'=>'确认你的新密码'], 'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fl\" style=\"display:none;\"></a><a href=\"javascript:void(0);\" class=\"pwd-btn right fl \"><span class=\"bg_l\"></span><span class=\"bg_r\"></span><i>隐藏</i></a></p></div>{error}"])->passwordInput() ?>
    <?= Html::submitButton('下一步', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'Send']) ?>
    <?php ActiveForm::end(); ?>


</section>

