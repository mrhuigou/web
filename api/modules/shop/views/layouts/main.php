<?php
use api\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?=\api\modules\shop\widgets\Header::widget();?>
        <div id="page">
        <div id="content" >
        <?= $content ?>
        </div>
        </div>
        <div id="footer">
            <div class="w990 bc">
                <div class="g_foot-ali">
                    <a href="">慧生活</a>
                    <b>|</b>
                    <a href="#">慧生活</a>
                    <b>|</b>
                    <a href="#">慧生活</a>
                    <b>|</b>
                    <a href="#">慧生活</a>
                    <b>|</b>
                    <a href="#">慧生活</a>
                    <b>|</b>
                    <a href="#">慧生活</a>
                    <b>|</b>
                    <a href="#">慧生活</a>
                    <b>|</b>
                    <a href="#">慧生活</a>
                    <b>|</b>
                    <a href="#">慧生活</a>
                    <b>|</b>
                    <a href="#">慧生活</a>
                    <b>|</b>
                    <a href="#">慧生活</a>
                </div>
                <div class="g_foot-nav">
                    <a href="#">关于慧生活</a>
                    <a href="#">合作伙伴</a>
                    <a href="#">营销中心</a>
                    <a href="#">联系客服</a>
                    <a href="#">开放平台</a>
                    <a href="#">诚征英才</a>
                    <span class="grayc">家润电子科技有限公司版权所有</span>
                </div>
            </div>
        </div>
    <?=\api\modules\shop\widgets\Storecategory::widget();?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>