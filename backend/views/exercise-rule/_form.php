<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model api\models\V1\ExerciseRule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exercise-rule-form">
    <?php $form = ActiveForm::begin(); ?>
	<?php if(Yii::$app->request->get('exercise_id')){?>
		<?= $form->field($model, 'exercise_id')->hiddenInput(['value'=>Yii::$app->request->get('exercise_id')])->label(false) ?>
	<?php }else{?>
		<?= $form->field($model, 'exercise_id')->dropDownList(ArrayHelper::map(\api\models\V1\Exercise::find()->all(),'id', 'title')) ?>
	<?php } ?>
    <?= $form->field($model, 'is_subcription')->radioList([0=>'禁用',1=>'启用']) ?>

    <?= $form->field($model, 'order_days')->textInput() ?>

    <?= $form->field($model, 'order_count')->textInput() ?>

    <?= $form->field($model, 'order_total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_datas')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'start_time')->widget(\yii\widgets\MaskedInput::className(), [
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
