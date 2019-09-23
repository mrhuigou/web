<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title ='取消报名';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2 class="tc f18"><?= Html::encode($this->title) ?></h2>
</header>
<section class="veiwport">
    <div class="fool white clearfix">
        <em class="fl mr15 iconfont">&#xe61f;</em>
        <div class="fl w-per77 f12 lh150">
            <p class="f14 mb5">报名信息</p>
            <p class="f14 mb5" >真实姓名:
                    <?=Html::encode($model->data->username);?>
            </p>
            <p class="f14 mb5">电话:<?=\common\component\Helper\Helper::str_mid_replace($model->data->telephone)?></p>
        </div>
    </div>
    <table class="whitebg w tbp10 mt10">
        <tr class="f5bg ">
            <th width="60%" class="p10 tl fb">名称</th>
            <th width="20%" class="p10 fb">数量</th>
            <th  width="20%" class="p10 fb">金额</th>
        </tr>
        <tr>
            <td >
                <?=$model->data->activity->title?>(<?=$model->data->activityItems?$model->data->activityItems->name:"免费报名"?>)
            </td>
            <td  class="tc">
                <?=$model->data->quantity?>
            </td>
            <td class="tc">
               <?=$model->data->total?>
            </td>
        </tr>
    </table>
<div class="pt10">
    <?php $form = ActiveForm::begin(['id' => 'login-form','fieldConfig' => [
        'errorOptions'=>['class'=>'error db']
    ],  ]); ?>
    <?=$form->field($model,'comment',['template'=>'{input}{error}'])->textarea(['class'=>'w noresize br5 fx-convert-tri p10','placeholder'=>'说下取消理由'])?>
    <?= Html::submitButton('取消报名', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'login-button']) ?>
    <?php ActiveForm::end(); ?>
</div>
</section>