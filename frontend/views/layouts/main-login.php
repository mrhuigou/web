<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */
$this->title = '每日惠购（mrhuigou.com）-青岛首选综合性同城网购-发现达人体验-分享同城生活';
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="description" content="每日惠购（mrhuigou.com）- 青岛首选综合性同城网购-每日惠购都市生活平台将城市生活分为吃、喝、穿、用、玩五大类，全面覆盖城市人生活的方方面面-每日惠购生活圈开放30余个生活体验版块，发现达人体验、分享网购乐趣-每日惠购为您量身打造高品质、高性价比、24小时上门服务的同城网购体验" />
    <meta name="keywords" content="每日惠购网、同城网购、网上购物、网购、网上商城、生活圈、慧生活网、网购商城" />
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
<body>
    <?php $this->beginBody() ?>
    <div id="header" style="margin-top:0!important;">
    <!--top-->
    <?=\frontend\widgets\HeaderTop::widget()?>
        <div id="login-header">
            <div class="w990 bc">
                <a href="/" target="_self"><img src="/assets/images/logo1.png" width="200" alt="每日惠购" class="db"></a>
            </div>
        </div>
    </div>
        <?= $content ?>
    <?=\frontend\widgets\Footer::widget()?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
