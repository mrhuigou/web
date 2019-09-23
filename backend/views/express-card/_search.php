<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ExpressCardSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="express-card-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'legal_person_name')?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'name') ?>

    <?php //$form->field($model, 'remark') ?>


    <?php  echo $form->field($model, 'begin_datetime')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
        'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]); ?>

    <?php  echo $form->field($model, 'end_datetime')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
        'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]); ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
