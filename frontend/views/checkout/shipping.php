<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='配送时间';
?>
<header class="header">
    <a href="/checkout/index" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
</header>
<section class="veiwport">
    <?php $form = ActiveForm::begin(['id' => 'form-address','fieldConfig' => [
        'template' => "<div class=\" whitebg  bdt p10\" ><span class=\"fb\">{label}：</span>{input}</div>{error}",
        'inputOptions' => ["maxlength"=>"40"],
        'errorOptions'=>['class'=>'red lh150 p5 whitebg']
    ],  ]);?>
    <?= $form->field($model, 'shipping_date')->dropDownList([
        date('Y-m-d'),
        date('Y-m-d',strtotime('+1 day')),
        date('Y-m-d',strtotime('+2 day')),
        date('Y-m-d',strtotime('+3 day')),
        date('Y-m-d',strtotime('+4 day')),
        date('Y-m-d',strtotime('+5 day')),
        date('Y-m-d',strtotime('+6 day')),

    ]) ?>
    <?= $form->field($model, 'shipping_time')->dropDownList([
        1=>'08:00-12:00',
        2=>'12:00-18:00',
        3=>'18:00-22:00'
    ]) ?>

    <div class="fixed-bottom tc">
        <?= Html::submitButton('确认保存', ['class' => 'appbtn redbtn pr15 pl15', 'name' => 'save-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <dl class="db p10 whitebg mt10 mb10 bdt lh150 ">
        <dt class="fb">当日达配送须知:</dt>
        <dd class="lh150" >
            00:00-12:00支付成功的订单，当日18:00前送达；<br/>
            12:00-17:00支付成功的订单，当日22:00前送达；<br/>
            17:00-24:00支付成功的订单，第二天12:00前送达；<br/>
            <em class="red">注：若因天气等因素影响，可能会顺延24小时送达！</em>
        </dd>
    </dl>
</section>