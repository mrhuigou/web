<?php
/* @var $this yii\web\View */
/* @var $this yii\web\View */
$this->title = '编辑地址';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="" style="min-width:1100px;">
	<div class="w1100 bc ">
		<!--面包屑导航-->
		<?= \yii\widgets\Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			'tag' => 'p',
			'options' => ['class' => 'gray6 pb5 pt5'],
			'itemTemplate' => '<a class="f14">{link}</a> > ',
			'activeItemTemplate' => '<a class="f14">{link}</a>',
		]) ?>
		<div class="bc  clearfix simsun">
			<div class="fl w100 mr10 menu-tree">
				<?= frontend\widgets\UserSiderbar::widget() ?>
			</div>
			<div class="fl w990 ">
				<?= $this->render('_form', [
					'model' => $model
				]) ?>
			</div>
		</div>
	</div>
</div>
