<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='修改支付密码';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
    <a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>
<section class="login-bodyer">
    <?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]);?>

    <?= $form->field($model, 'password',["inputOptions"=>['placeholder'=>'新密码','class' => 'input-text w telephone','type'=>'password','maxlength'=>6,'minlength'=>6]])->label("新密码") ?>
    <?= $form->field($model, 'password_repeat',["inputOptions"=>['placeholder'=>'重复新密码','class' => 'input-text w telephone','type'=>'password','maxlength'=>6,'minlength'=>6]])->label("重复新密码") ?>

    <?= Html::submitButton('提交密码', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'realname-button']) ?>
    <?php ActiveForm::end(); ?>

</section>