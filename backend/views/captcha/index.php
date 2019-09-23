<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '验证码';
$this->params['breadcrumbs'][] = $this->title;
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
				<?= Html::encode($this->title) ?> <small>监控、统计、分析</small>
			</h3>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->

	<div class="coupon-index">
		<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				'phone',
				'code',
				'status',
				'date_added'
			],
		]); ?>

	</div>
</div>