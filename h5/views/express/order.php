<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/15
 * Time: 11:20
 */
$this->title="配送订单";
?>
<header class="fx-top bs-bottom whitebg lh44">
	<div class="flex-col tc">
		<a class="flex-item-2" href="/express/index">
			<i class="aui-icon aui-icon-home green f28"></i>
		</a>
		<div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($this->title) ?>
		</div>
		<a class="flex-item-2 refresh_btn" href="javascript:location.reload(true);" >
			<i class="aui-icon aui-icon-refresh green f28"></i>
		</a>
	</div>
</header>
<div class="w pt50">
<?php
echo \yii\widgets\ListView::widget([
	'dataProvider' => $dataProvider,
	'itemOptions' => ['class' => 'item'],
	'emptyText'=>'<figure class="info-tips whitebg gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有信息</figcaption></figure>',
	'itemView' => '_item_view',
	'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
		'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
		'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
		'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
	]
]);
?>
</div>
