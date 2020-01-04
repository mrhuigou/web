<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '我的优惠券';
?>
<style>
    .activity-1-coupon::after, .activity-1-coupon::before {
        background-color: #f4f4f4;
    }
</style>
<?= h5\widgets\Header::widget(['title' => $this->title]) ?>
<section class="veiwport  pb50">
<!--	<div class="p10 ">-->
<!--		<a class="btn mbtn bluebtn pr10 pl10 w" href="--><?//= \yii\helpers\Url::to(['/user-coupon/card']) ?><!--"> 我要激活优惠券 </a>-->
<!--	</div>-->
	<?= \yii\widgets\ListView::widget([
		'layout' => "{items}\n{pager}",
		'dataProvider' => $dataProvider,
		'options' => ['class' => 'list-view'],
		'itemOptions' => ['class' => 'item m10 '],
		'emptyText' => '<figure class="info-tips  gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有信息</figcaption></figure>',
		'itemView' => '_item_view',
		'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
			'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
			'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
			'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
		]
	]);
	?>
</section>
<!--浮动购物车-->
<?= h5\widgets\MainMenu::widget(); ?>


