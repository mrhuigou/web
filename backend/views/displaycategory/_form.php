<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;

?>
<div class="displaycategory-form">
	<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'title')->textInput() ?>
	<div class="form-group">
		<label class="control-label" for="displaycateform-pid">所属上级名称</label>
		<span class="form-control"><?= $model->pid_name ?></span>
	</div>
	<?= $form->field($model, 'pid')->widget(\yii\jui\AutoComplete::className(), [
		'options' => ['class' => 'form-control'],
		'clientOptions' => [
			'source' => "/displaycategory/auto-complete",
		],
	]) ?>

	<?= $form->field($model, 'image')->widget(
		FileAPI::className(),
		[
			'settings' => [
				'url' => ['/displaycategory/fileapi-upload']
			]
		]
	) ?>

	<?= $form->field($model, 'relate_cate')->textarea() ?>
	<?= $form->field($model, 'sort')->textInput() ?>
	<?= $form->field($model, 'status')->radioList([0 => "关闭", 1 => "启用"]) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	<?php ActiveForm::end(); ?>
</div>