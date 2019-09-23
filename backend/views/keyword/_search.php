<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\KeywordSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="keyword-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'keyword_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'review') ?>

    <?= $form->field($model, 'weight') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'url') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
