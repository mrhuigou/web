<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;

/* @var $this yii\web\View */
$this->title = '我的推广';
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
			<div class="p5 m5 bd lh150 bg-wh f12 f5">
				友情提示：亲，您推广的用户订单返佣，系统会在订单交易完成后，自动转入你的账户余额中。
			</div>
			<?= ListView::widget([
				'dataProvider' => $dataProvider,
				'layout' => '{items}{pager}',
				'itemOptions' => ['class' => 'item m5'],
				'emptyText'=>'<figure class="info-tips bg-wh gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前任何推广信息</figcaption></figure>',
				'emptyTextOptions' => ['class' => 'empty tc p10 '],
				'itemView' => 'promoter-list-item',
				'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
					'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
					'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
					'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
				]
			]); ?>
		</div>
	</section>
<?= h5\widgets\MainMenu::widget(); ?>