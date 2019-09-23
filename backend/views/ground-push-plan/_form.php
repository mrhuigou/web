<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\GroundPushPlan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ground-push-plan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'begin_date_time')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("开始时间");?>

    <?= $form->field($model, 'end_date_time')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("结束时间"); ?>

    <?= $form->field($model, 'shipping_end_time')->textInput(); ?>

    <?= $form->field($model, 'contact_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'ground_push_point_id')->dropDownList(\yii\helpers\ArrayHelper::map(\api\models\V1\GroundPushPoint::find()->all(), 'id', 'name')) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
