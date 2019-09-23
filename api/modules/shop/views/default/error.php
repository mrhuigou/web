<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>

<div class="errorPage" style="margin: 0 auto; width: 990px;">
    <div class="errorDetail">
        <h2><?= Html::encode($this->title) ?></h2>
        <div class="errorAdvice">
            <p>您可以： <?= nl2br(Html::encode($message)) ?></p>
            <ol>
                <li>检查刚才的输入</li>
                <li>
                    去其他地方逛逛：
                </li>
            </ol>
        </div>
    </div>
</div>