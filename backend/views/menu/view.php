<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
	<div class="btn-group">
		<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Delete', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => 'Are you sure you want to delete this item?',
				'method' => 'post',
			],
		])
		?>
	</div>
	<div class="menu-view">
		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				'menuParent.name:text:Parent',
				'name',
				'route',
				'order',
			],
		])
		?>
	</div>
</div>