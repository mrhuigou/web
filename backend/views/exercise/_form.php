<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Exercise */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exercise-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'begin_time')->widget(\yii\widgets\MaskedInput::className(), [
		'mask' => '9999-99-99 99:99:99',
	]);
	?>
	<?= $form->field($model, 'end_time')->widget(\yii\widgets\MaskedInput::className(), [
		'mask' => '9999-99-99  99:99:99',
	]);
	?>

    <?= $form->field($model, 'status')->radioList([0=>'禁用',1=>'启用']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
