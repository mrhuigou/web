<?php
/* @var $this yii\web\View */
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\DataColumn;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
$this->title = '修改密码';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <!-- BEGIN STYLE CUSTOMIZER -->
    <?=\affiliate\widgets\Customizer::widget();?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                修改密码 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <?php $form = ActiveForm::begin(['id' => 'password-form','fieldConfig' => [
        'labelOptions'=>['class'=>'control-label visible-ie8 visible-ie9'],
         'inputOptions' => ['class' => 'form-control placeholder-no-fix',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'help-block']
    ]]); ?>
    <?= $form->field($model, 'oldpassword',["inputOptions"=>['placeholder'=>'原密码'],'template'=>"{label}<div class=\"input-icon\"><i class=\"fa fa-user\"></i>{input}</div><span  class=\"help-block\">{error}</span>"])->passwordInput() ?>
    <?= $form->field($model, 'password',["inputOptions"=>['placeholder'=>'密码'],'template'=>"{label}<div class=\"input-icon\"><i class=\"fa fa-lock\"></i>{input}</div><span  class=\"help-block\">{error}</span>"])->passwordInput() ?>
    <?= $form->field($model, 'password_repeat',["inputOptions"=>['placeholder'=>'重复密码'],'template'=>"{label}<div class=\"input-icon\"><i class=\"fa fa-lock\"></i>{input}</div><span  class=\"help-block\">{error}</span>"])->passwordInput() ?>
    <div class="form-actions">
        <button type="submit" class="btn green pull-right">
            提交 <i class="m-icon-swapright m-icon-white"></i>
        </button>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<!-- END LOGIN -->
</div>