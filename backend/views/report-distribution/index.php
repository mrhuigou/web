<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '每日配送量';
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
				每日配送量<small>监控、统计、分析</small>
			</h3>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<div class="return-base-index">
		<?php echo $this->render('_search', ['model' => $searchModel]); ?>
		<div id="w2" class="grid-view"><div class="summary">共<b><?=count($dataProvider)?></b>条数据.</div>
			<table class="table table-striped table-bordered"><thead>
				<tr><th>日期</th><th>全部订单数</th><th>平台订单数</th><th>快递订单数</th></tr>
				</thead>
				<tbody>
				<?php if($dataProvider){?>
					<?php foreach ($dataProvider as $key=>$value){?>
						<tr data-key="<?=$key?>"><td><?=$value['DATE_TIME']?></td><td><?=$value['ALL_QTY']?></td><td><?=$value['PLAT_QTY']?></td><td><?=$value['EXPRESS_QTY']?></td></tr>
					<?php }?>
				<?php }else{?>
					<tr data-key="0"><td>没有任何信息</td></tr>
				<?php }?>
				</tbody></table>
		</div>
	</div>
</div>