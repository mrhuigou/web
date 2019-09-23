<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\RechargeCardSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recharge-card-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'card_no') ?>

    <?php  $form->field($model, 'value') ?>

    <?php echo $form->field($model, 'title') ?>
    
    <?php echo $form->field($model, 'card_code') ?>

    <?php echo $form->field($model, 'card_pin') ?>

    <?php echo $form->field($model, 'start_time')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99 99:99:99',
      ]) ?>

    <?php echo $form->field($model, 'end_time')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99 99:99:99',
      ]) ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php echo $form->field($model, 'status')->dropdownList(['-1'=>'全部', '0'=>'未激活','1'=>'已激活']) ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
