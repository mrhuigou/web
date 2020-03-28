<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/17
 * Time: 11:15
 */
$this->title="我的二维码";
?>
<header class="header">
	<div class="flex-col tc">
		<a class="flex-item-2" href="javascript:history.back();">
			<i class="aui-icon aui-icon-left green f28"></i>
		</a>
		<div class="flex-item-8 f16">
			<?=\yii\bootstrap\Html::encode($this->title)?>
		</div>
		<a class="flex-item-2" href="<?php echo \yii\helpers\Url::to(['/user/edit-myinfo'])?>">
			<i class="aui-icon aui-icon-share green f28"></i>
		</a>
	</div>
</header>
<div class="pt50 bc db ">
	<div class="m10 tc">
		<h2><?=Yii::$app->user->identity->nickname?Yii::$app->user->identity->nickname:'匿名'?></h2>
		<div id="pay_scan_code" class="tc  bc  bg-wh m10 p10 " style="width: 300px;height: auto;"></div>
		<p>扫一扫，邀请好友加入</p>
	</div>
</div>
<?php $this->beginBlock('JS_END') ?>
	var qrcode = new QRCode('pay_scan_code', {
	text: '<?=\yii\helpers\Url::to(['/site/index','follower_id'=>Yii::$app->user->getId()],true)?>',
	width: 280,
	height: 280,
	colorDark : '#000000',
	colorLight : '#ffffff',
	correctLevel : QRCode.CorrectLevel.H
	});
<?php $this->endBlock('JS_END') ?>
<?php
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_END);
?>