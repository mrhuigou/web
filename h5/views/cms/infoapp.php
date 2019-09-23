<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title =$model->description->title;
?>
<meta charset="UTF-8" />
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<meta content="_csrf" name="csrf-param" />
<meta content="U3ZmNlFGM2McIi9VGyJSLTkcNnQUGVQwBjA/ZilxQCAfMl93AxlkLA==" name="csrf-token" />
<title></title>
<link href="/assets/stylesheet/webapp.css" rel="stylesheet" />
<link href="/assets/stylesheet/idangerous.swiper.css" rel="stylesheet" />
<style type="text/css">.nonebd{border:0!important;}
    .nonebd li{background: none;border:0!important;}
</style>

<!--top-->
<section class="veiwport" style="margin-top:0px">
    <?=Html::decode($model->description->description)?>
</section>