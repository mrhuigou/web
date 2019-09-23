<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this  yii\web\View */
/* @var $model mdm\admin\models\BizRule */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\BizRule */
$this->title = '规则列表';
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
	<p>
		<?= Html::a('新增规则', ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<div class="role-index">
		<?=
		GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
				[
					'attribute' => 'name',
					'label' => '名称',
				],
				['class' => 'yii\grid\ActionColumn',],
			],
		]);
		?>

	</div>
</div>