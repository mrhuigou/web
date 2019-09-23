<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\StartPage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="start-page-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'type')->radioList(['1'=>'PC端','2'=>'移动端']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(\common\extensions\widgets\kindeditor\KindEditor::className(),
        ['clientOptions' => ['allowFileManager' => 'true','allowUpload' => 'true','filterMode'=>false]])  ?>

    <?= $form->field($model, 'frequency')->radioList(['当前会话1次','每天1次']) ?>

    <?= $form->field($model, 'date_start')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
        'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]); ?>

    <?= $form->field($model, 'date_end')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
        'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]); ?>

    <?= $form->field($model, 'status')->radioList(['禁用','启用']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
