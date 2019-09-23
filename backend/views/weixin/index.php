<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/6
 * Time: 14:53
 */
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use \Yii;
$this->title = "微信菜单";
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
				<?= Html::encode($this->title) ?> <small>菜单设置</small>
			</h3>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<?=print_r($model);?>
</div>
