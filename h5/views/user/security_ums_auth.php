<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='实名认证';
?>

<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
    <a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>

<section class="login-bodyer">
    用于提升账号的安全性和信任级别,会影响到某些功能的使用如货到卡付等。认证后不能修改认证信息。
    <?php if(Yii::$app->getSession()->getFlash('error')){?>
        <div class="login-msg bc mt20">
            <p class="org mb5">
                认证失败！<?= Yii::$app->getSession()->getFlash('error'); ?>
            </p>
        </div>
    <?php } ?>
    <?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]);?>
    <?= $form->field($model, 'name',["inputOptions"=>['placeholder'=>'请输入真实姓名','class' => 'input-text w telephone']])->label("真实姓名") ?>

    <?= $form->field($model, 'cert',["inputOptions"=>['placeholder'=>'请输入身份证号码','class' => 'input-text w telephone']])->label("身份证号码") ?>
    <?= $form->field($model, 'card',["inputOptions"=>['placeholder'=>'请输入银行号码','class' => 'input-text w telephone']])->label("银联卡号") ?>

    <?= Html::submitButton('实名认证', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'realname-button']) ?>
    <?php ActiveForm::end(); ?>

</section>

