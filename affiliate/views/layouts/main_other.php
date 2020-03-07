<?php
use yii\helpers\Html;
use affiliate\assets\AppAsset;
/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta property="qc:admins" content="1013706074652112566375" />
	<meta name="baidu-site-verification" content="YfBJrvVnS9" />
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<meta name="description" content="每日惠购（www.mrhuigou.com）- 青岛首选综合性同城网购-每日惠购都市生活平台将城市生活分为吃、喝、穿、用、玩五大类，全面覆盖城市人生活的方方面面-每日惠购生活圈开放30余个生活体验版块，发现达人体验、分享网购乐趣-每日惠购为您量身打造高品质、高性价比、24小时上门服务的同城网购体验" />
	<meta name="keywords" content="每日惠购网、同城网购、网上购物、网购、网上商城、生活圈、慧生活网、网购商城" />

	<link rel="shortcut icon" href="/icons/favicon.ico"/>
	<link rel="apple-touch-icon" href="/icons/apple-touch-icon-76.png" sizes="76x76">
	<link rel="apple-touch-icon" href="/icons/apple-touch-icon-120.png" sizes="120x120">
	<link rel="apple-touch-icon" href="/icons/apple-touch-icon-152.png" sizes="152x152">
	<link rel="apple-touch-icon" href="/icons/apple-touch-icon-180.png" sizes="180x180">
	<?php $this->head() ?>
	<?php
	if(strpos(strtolower(\Yii::$app->request->getUserAgent()), 'micromessenger')){?>
	<?php }else{?>
<!--		<link href="/assets/stylesheet/flex-m.css" rel="stylesheet">-->
	<?php } ?>
	<!--[if lt IE 9]>
	<script src="/assets/script/html5shiv.js"></script>
	<script src="/assets/script/respond.min.js"></script>
	<script src="/assets/script/ie8.js"></script>
	<![endif]-->
	<?php if(!YII_ENV_TEST){?>
		<script>
            var _hmt = _hmt || [];
            (function() {
                var hm = document.createElement("script");
                hm.src = "https://hm.baidu.com/hm.js?b5eebc3bd201f8ba90e451ecb457a265";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            })();
		</script>
	<?php } ?>
</head>
<body>
<div style="display: none">
    <?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){ ?>
        <img src="/assets/images/zhqd/logo_300x300.jpeg" alt="智慧青岛"  />
    <?php }else{?>
        <img src="/assets/images/logo_300x300.png" alt="每日惠购"  />
    <?php }?>
</div>
<?php $this->beginBody() ?>
<article>
	<?= $content ?>
</article>
<div id="pop_sku">
</div>
<!--微信分享的提示层-->
<div class="share-guide-pop tr">
	<img src="/assets/images/share.png" alt="分享朋友圈">
</div>
<?php $this->beginBlock("JS")?>
(function (doc, win) {
var docEl = doc.documentElement,
resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
recalc = function () {
var clientWidth = docEl.clientWidth;
if (!clientWidth){
return;
}else if(clientWidth>640){
docEl.style.fontSize =20 + 'px';
}else{
docEl.style.fontSize = 20 * (clientWidth / 640) + 'px';
}
};
if (!doc.addEventListener) return;
win.addEventListener(resizeEvt, recalc, false);
doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);
<?php $this->endBlock()?>
<?php
$this->registerJs($this->blocks['JS'],\yii\web\View::POS_HEAD);
?>
<?php
$this->endBody() ;
?>
</body>
</html>
<?php $this->endPage() ?>
