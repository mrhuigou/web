<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\RechargeCard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recharge-card-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'value')->textInput() ?>
    <?= $form->field($model, 'quantity')->textInput() ?>
    <?= $form->field($model, 'card_no')->textInput() ?>
    <?= $form->field($model, 'card_code')->textInput(['maxlength' => 32]) ?>
    <?= $form->field($model, 'start_time')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99 99:99:99',
      ]) ?>
    <?= $form->field($model, 'end_time')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99 99:99:99',
      ]) ?>
    <?= $form->field($model, 'status')->radioList(['0'=>'未激活','1'=>'已激活']) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '生成' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
