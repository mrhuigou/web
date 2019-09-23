<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Address */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="address-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'address_1')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'postcode')->textInput(['maxlength' => 10]) ?>

    <?php $district = [7607=>'市南区',7608=>'市北区',7609=>'四方区',7611=>'崂山区',9390=>'李沧区',7613=>'城阳区',7610=>'黄岛区'];?>
    <?= $form->field($model, 'district_id')->dropDownList($district); ?>

    <?= $form->field($model, 'telephone')->textInput(['maxlength' => 255]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '保存' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
