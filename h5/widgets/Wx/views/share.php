<?php
$this->beginBlock('JS_SHARE') ?>
	wx.ready(function () {
	wx.showOptionMenu();
	var go_url='<?= $model['go_url'] ?>';
	var back_url='<?= $model['back_url'] ?>'
	var shareData = {
	title: '<?= $model['title'] ?>',
	desc: '<?= $model['desc'] ?>',
	link: '<?= $model['link'] ?>',
	imgUrl: '<?= $model['imgUrl'] ?>',
	success:function () {
	if(go_url){
	location.href=go_url;
	}
	},
	cancel: function () {
	if(back_url){
	location.href=back_url;
	}
	},
	};
	wx.onMenuShareAppMessage(shareData);
	wx.onMenuShareTimeline(shareData);
	wx.onMenuShareWeibo(shareData);
	wx.onMenuShareQQ(shareData);
	wx.onMenuShareQZone(shareData);
	});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_SHARE'], \yii\web\View::POS_END);
?>