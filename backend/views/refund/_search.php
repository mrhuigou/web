<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use api\models\V1\ReturnStatus;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ReturnBaseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="return-base-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-horizontal'],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-4\">{error}</div>",  
            'labelOptions' => ['class' => 'col-md-1 control-label','style'=>'width:90px;'],  
        ], 
    ]); ?>

    <?php //echo $form->field($model, 'return_id') ?>

    <?php //echo $form->field($model, 'return_code') ?>

    <?php echo $form->field($model, 'order_id') ?>

    <?php //echo $form->field($model, 'order_code') ?>

    <?php echo $form->field($model, 'order_no') ?>

    <?php // echo $form->field($model, 'date_ordered') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php echo $form->field($model, 'firstname') ?>

    <?php // echo $form->field($model, 'lastname') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php echo $form->field($model, 'telephone') ?>

    <?php // echo $form->field($model, 'return_status_id') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'date_added') ?>

    <?php // echo $form->field($model, 'date_modified') ?>

    <?php // echo $form->field($model, 'send_status') ?>

    <?php // echo $form->field($model, 'is_all_return') ?>

    <?php  echo  $form->field($model, 'begin_date')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
      ]);
    ?>
        <?php  echo  $form->field($model, 'end_date')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
      ]);
    ?>

    <?=$form->field($model, 'return_status_id')->dropDownList(ArrayHelper::merge(['0'=>'全部'],ArrayHelper::map(ReturnStatus::find()->all(),'return_status_id', 'name')));?>


    <div class="form-actions top fluid ">
        <div class="col-md-offset-1 col-md-9" style="margin-left: 90px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
