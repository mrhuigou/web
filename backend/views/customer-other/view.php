<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\CustomerOtherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '报名管理';
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
				<small>监控、统计、分析</small>
			</h3>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>

	<p>
		<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Delete', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => 'Are you sure you want to delete this item?',
				'method' => 'post',
			],
		]) ?>
	</p>

	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'customer_id',
			'realname',
			'telephone',
			'quantity',
			'company',
			'position',
			'address',
			'industry',
			'service:ntext',
			'remark:ntext',
			'status',
			'data_added',
		],
	]) ?>

</div>
