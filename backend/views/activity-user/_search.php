<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ClubActivityUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="club-activity-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'activity_id') ?>
    <?= Html::hiddenInput('ClubActivityUserSearch[activity_id]',$activity?$activity->id:$model->activity_id)?>

    <?php // echo $form->field($model, 'activity_items_id') ?>

    <?php // echo $form->field($model, 'order_id') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'username') ?>

    <?php // echo $form->field($model, 'telephone') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php echo $form->field($model, 'status')->dropDownList(['2'=>'全部','0'=>'未报名','1'=>'已报名']);?>

    <?php // echo $form->field($model, 'creat_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php  echo  $form->field($model, 'begin_date')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
      ]);
    ?>
        <?php  echo  $form->field($model, 'end_date')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
      ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?php //echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
