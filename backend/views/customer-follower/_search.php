<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\searchs\CustomerFollowerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-follower-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customer_id') ?>
    <?= $form->field($model, 'customer_name')->label("客户姓名") ?>


    <?= $form->field($model, 'status')->dropDownList([ 'all'=>'全部','0'=>'未购买','1'=>'已购买'])->label("粉丝是否购买过") ?>

    <?= $form->field($model, 'creat_at')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
        'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']])->label("创建时间"); ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
