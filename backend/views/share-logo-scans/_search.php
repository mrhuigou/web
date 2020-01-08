<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ShareLogoScansSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="share-logo-scans-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'share_logo_scans_id') ?>

    <?= $form->field($model, 'weixin_scans_id') ?>

    <?= $form->field($model, 'type') ?>
    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'parameter') ?>

    <?= $form->field($model, 'logo_url') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
