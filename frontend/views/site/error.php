<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="w1100 bc pt50 pb100 clearfix">
    <img src="/assets/images/upgrade/error_img1.jpg" alt="404" width="477" height="391" class="fl" />
    <div class="fr w450 pr50 f14 pt50">
        <div class="errorB">
            <?= nl2br(Html::encode($message)) ?>
        </div>
        <p class="lh180 mt50 ml10">
            非常抱歉给您造成不便，我们建议您：<br />
            <span class="f18">&middot;</span> 返回 <a href="/" class="green">家润同城网购首页</a> <br />
            <span class="f18">&middot;</span> 联系我们的客服人员 <span class="green">4008-556-977</span>
        </p>
    </div>
</div>