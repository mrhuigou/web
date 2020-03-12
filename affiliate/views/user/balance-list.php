<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;

/* @var $this yii\web\View */
$this->title = '我的余额明细';
?>
<header class="fx-top bs-bottom whitebg lh44">
	<div class="flex-col tc">
		<a class="flex-item-2" href="javascript:history.back();">
			<i class="aui-icon aui-icon-left gray6 f28"></i>
		</a>

		<div class="flex-item-8 f16">
			<?= Html::encode($this->title) ?>
		</div>
		<a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/user/index']) ?>">
			<i class="aui-icon aui-icon-people gray6 f28"></i>
		</a>
	</div>
</header>
<section class="veiwport">
	<div class="pb50 pt50">
		<div class="pt10"></div>
		<?= ListView::widget([
			'dataProvider' => $dataProvider,
			'layout' => '{items}{pager}',
			'itemOptions' => ['class' => 'item'],
			'emptyTextOptions' => ['class' => 'empty tc p10 '],
			'itemView' => 'balance-list-item',
			'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
				'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
				'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
				'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
			]
		]); ?>
	</div>
</section>
<?= h5\widgets\MainMenu::widget(); ?>
