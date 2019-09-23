<?php
$this->registerJsFile("https://res.wx.qq.com/open/js/jweixin-1.0.0.js",['depends'=>[\h5\assets\AppAsset::className()]]);
$this->beginBlock('JS_INIT') ?>
wx.config({
debug: false,
appId: '<?php echo $signPackage["appId"];?>',
timestamp: <?php echo $signPackage["timestamp"];?>,
nonceStr: '<?php echo $signPackage["nonceStr"];?>',
signature: '<?php echo $signPackage["signature"];?>',
jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone','chooseImage','previewImage','uploadImage','downloadImage','getNetworkType','openLocation','getLocation','scanQRCode']
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_INIT'],\yii\web\View::POS_END);
?>