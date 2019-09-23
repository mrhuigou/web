<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\V1\WeixinMenu */

$this->title = '分类设置';
$this->params['breadcrumbs'][] = ['label' => '显示分类', 'url' => ['index']];
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
				<?= Html::encode($this->title) ?>
			</h3>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->

		</div>
	</div>
	<?php if($model){?>
		<ul>
		<?php foreach ($model as $value){?>
			<li>
				<?=$value->description->name?>
				<?php if($value->getChild()){?>
					<ul class="list-inline">
						<?php foreach ($value->getChild() as $val){?>
						<li ><a href="<?=\yii\helpers\Url::to(['update','id'=>$val->category_display_id])?>" class="<?=$val->image?"text-danger":""?>" ><?=$val->description->name?></a></li>
						<?php }?>
					</ul>
				<?php }?>
			</li>
		<?php } ?>
		</ul>
	<?php }?>
</div>
