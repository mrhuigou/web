<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Assignment */
/* @var $fullnameField string */
$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
	$userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);
$this->title = '分配' . ' : ' . $userName;
$this->params['breadcrumbs'][] = ['label' => '分配', 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;
$opts = Json::htmlEncode([
	'items' => $model->getItems()
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>
<div class="page-content">
	<!-- BEGIN STYLE CUSTOMIZER -->
	<?= \backend\widgets\Customizer::widget(); ?>
	<!-- END STYLE CUSTOMIZER -->
	<!-- BEGIN PAGE HEADER-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->
			<h3 class="page-title">
				<?= Html::encode($this->title) ?>
			</h3>
			<?= \yii\widgets\Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<div class="assignment-index">
		<div class="row">
			<div class="col-sm-5">
				<input class="form-control search" data-target="avaliable"
				       placeholder="搜索可用的">
				<select multiple size="20" class="form-control list" data-target="avaliable">
				</select>
			</div>
			<div class="col-sm-1">
				<br><br>
				<?= Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => (string)$model->id], [
					'class' => 'btn btn-success btn-assign',
					'data-target' => 'avaliable',
					'title' => '分配'
				]) ?><br><br>
				<?= Html::a('&lt;&lt;' . $animateIcon, ['revoke', 'id' => (string)$model->id], [
					'class' => 'btn btn-danger btn-assign',
					'data-target' => 'assigned',
					'title' => '移除'
				]) ?>
			</div>
			<div class="col-sm-5">
				<input class="form-control search" data-target="assigned"
				       placeholder="搜索指定的">
				<select multiple size="20" class="form-control list" data-target="assigned">
				</select>
			</div>
		</div>
	</div>
</div>