<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\GroundPushPoint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ground-push-point-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zone_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zone_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'district_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'district_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>
    <?= $form->field($model, 'pass')->textInput() ?>
    <?= $form->field($model, 'leaflet')->textInput() ?>

    <?= $form->field($model, 'create_at')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("创建时间"); ?>

    <?= $form->field($model, 'update_at')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("更新时间"); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
