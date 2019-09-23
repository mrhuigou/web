<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\CustomerOther */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-other-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'realname')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'industry')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'service')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'status')->radioList(['待审核','审核通过']) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
