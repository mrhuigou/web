<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\GroundPushStock */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ground-push-stock-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ground_push_point_id')->dropDownList(\yii\helpers\ArrayHelper::merge(['0' => '全部'], \yii\helpers\ArrayHelper::map(\api\models\V1\GroundPushPoint::find()->all(), 'id', 'name'))) ?>

    <?= $form->field($model, 'product_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'tmp_qty')->textInput() ?>

    <?= $form->field($model, 'last_time')->textInput() ?>

    <?= $form->field($model, 'version')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
