<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\WeixinScans */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="weixin-scans-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1_1" data-toggle="tab">文本消息</a>
            </li>
            <li>
                <a href="#tab_1_2" data-toggle="tab">图片消息</a>
            </li>
            <li>
                <a href="#tab_1_3" data-toggle="tab">语音消息</a>
            </li>
            <li>
                <a href="#tab_1_4" data-toggle="tab">视频消息</a>
            </li>
            <li>
                <a href="#tab_1_5" data-toggle="tab">图文消息</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="tab_1_1" class="tab-pane active">
                <div class="panel panel-default ">
                    <div class="panel-body">
                        文本消息
                    </div>
                </div>
            </div>
            <div id="tab_1_2" class="tab-pane">
                <div class="panel panel-default ">
                    <div class="panel-body">
                        图片消息
                    </div>
                </div>
            </div>
            <div id="tab_1_3" class="tab-pane">
                <div class="panel panel-default ">
                    <div class="panel-body">
                        语音消息
                    </div>
                </div>
            </div>
            <div id="tab_1_4" class="tab-pane">
                <div class="panel panel-default ">
                    <div class="panel-body">
                        视频消息
                    </div>
                </div>
            </div>
            <div id="tab_1_5" class="tab-pane">
                <div class="panel panel-default ">
                    <div class="panel-body">
                        图文消息
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?=$form->field($model, 'touser')->textarea(['rows'=>5]);?>
	<?= $form->field($model, 'msgtype')->hiddenInput() ?>



	<?= $form->field($model, 'status')->radioList(['稍后推送','立刻推送']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
