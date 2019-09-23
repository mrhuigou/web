<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Keyword */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="keyword-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?php //echo $form->field($model, 'review')->textInput() ?>

    <?= $form->field($model, 'weight')->textInput() ?>
    <?php $model->status=$model->status!==0?1:0?>
    <?= $form->field($model, 'status',['options'=>['class'=>'col-md-12']])->radioList(['0'=>'禁用','1'=>'启用']); ?>

    <?php $color = ['gray9'=>'灰色','green'=>'绿色','red'=>'红色','blue'=>'蓝色'];?>
    <?= $form->field($model, 'color')->dropDownList($color); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '保存' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
