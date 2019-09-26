<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

//$this->title = '家润慧生活（mrhuigou.com）-青岛首选综合性同城网购-发现达人体验-分享同城生活';
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="description" content="家润慧生活（mrhuigou.com）- 青岛首选综合性同城网购-家润都市生活平台将城市生活分为吃、喝、穿、用、玩五大类，全面覆盖城市人生活的方方面面-家润生活圈开放30余个生活体验版块，发现达人体验、分享网购乐趣-家润慧生活为您量身打造高品质、高性价比、24小时上门服务的同城网购体验" />
    <meta name="keywords" content="家润网、同城网购、网上购物、网购、网上商城、生活圈、慧生活网、网购商城" />
    <?= Html::csrfMetaTags() ?>
    <meta name="baidu-site-verification" content="QdIGxb2KIu" />
    <meta property="wb:webmaster" content="d1e6bf02c356ba52" />
    <meta property="qc:admins" content="101370626063652112566375" />
    <meta name="baidu-site-verification" content="926b1720a194578b7d2289290646cc0f"/>
    <link rel="dns-prefetch" href="//www.mrhuigou.com" />
    <link rel="dns-prefetch" href="//img1.mrhuigou.com" />
    <link rel="dns-prefetch" href="//g.tbcdn.cn" />
    <link rel="shortcut icon" href="/favicon.ico"/>
    <?php $this->head() ?>
    <?php if(!YII_ENV_TEST){?>
        <script>
            var _hmt = _hmt || [];
            (function() {
                var hm = document.createElement("script");
                hm.src = "//hm.baidu.com/hm.js?b561d34e46cf6323aa74802a413ed8e1";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            })();
        </script>
    <?php }?>
</head>
<body >
<?php $this->beginBody() ?>
<?=\frontend\widgets\HeaderNew::widget()?>
<?= $content ?>
<?=\frontend\widgets\Footer::widget()?>
<?php $this->endBody() ?>
<?php $this->registerJs('$("img.lazy").scrollLoading();')?>
</body>
</html>
<?php $this->endPage() ?>
