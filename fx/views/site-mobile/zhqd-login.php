<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '用户登录认证中';
?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="/express/index">
            <i class="aui-icon aui-icon-left green f28"></i>
        </a>
        <div class="flex-item-8 f16">
            <?= \yii\helpers\Html::encode($this->title) ?>
        </div>
        <a class="flex-item-2 refresh_btn" href="javascript:location.reload(true);" >
            <i class="aui-icon aui-icon-refresh green f28"></i>
        </a>
    </div>
</header>
<div class="tc db wh-bg w  pl10 pr10" style="padding-top: 10em;">
    <p class="p10 lh200 mb20">用户登录认证中,如有疑问请点击</p>
    <a class="btn lbtn greenbtn w" href="javascript:location.reload(true);">刷新本页</a>
</div>
<?=\h5\widgets\App\Login::widget()?>