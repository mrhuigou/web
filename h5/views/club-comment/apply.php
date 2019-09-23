<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title ='发表点评';
?>
<header class="header w" id="header">
    <a href="<?=Yii::$app->request->referrer?>" class="his-back">返回</a>
    <h2 class="tc f18"><?= Html::encode($this->title) ?></h2>
</header>
<style>
    .checkbox{ display: inline-block;}
</style>
<section class="veiwport">
    <div class="whitebg p10" style="min-height: 420px;">
        <?php $form = ActiveForm::begin(['id' => 'login-form','fieldConfig' => [
            'errorOptions'=>['class'=>'error db']
        ],  ]); ?>
        <?=$form->field($model,'tag',['template'=>'{input}'])->checkboxList($model->taglist)?>
        <?= $form->field($model, 'content',['template' => "<div class=\"pr bd br5 p10\">{input}</div>{error}"])->textarea(['class'=>'w noresize br5 fx-convert-tri','placeholder'=>'这一刻的想法']) ?>
        <?=$form->field($model,'address',['template'=>'{input}'])->hiddenInput()?>
        <?=$form->field($model,'images',['template'=>'{input}'])->hiddenInput()?>
        <?=h5\widgets\Wx\Image::widget()?>
        <div class="p10">
            <?= Html::submitButton('提交', ['class' => 'btn mbtn w white bc bluebg', 'name' => 'save-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</section>
