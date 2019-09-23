<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/28
 * Time: 16:20
 */
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;
$this->title="编辑分类";
?>
<div class="page-content">
	<!-- BEGIN STYLE CUSTOMIZER -->
	<?=\backend\widgets\Customizer::widget();?>
	<!-- END STYLE CUSTOMIZER -->
	<!-- BEGIN PAGE HEADER-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->
			<h3 class="page-title">
				<?= Html::encode($this->title) ?>
			</h3>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->

		</div>
	</div>
	<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'image')->widget(
		FileAPI::className(),
		[
			'settings' => [
				'url' => ['/displaycategory/fileapi-upload']
			]
		]
	) ?>
	<?= $form->field($model, 'url')->textInput() ?>
	<div class="form-group">
		<?= Html::submitButton('Update', ['class' =>'btn btn-primary']) ?>
	</div>
	<?php ActiveForm::end(); ?>
</div>

