<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'username')->textInput() ?>
    <?php echo $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?php echo $form->field($model, 'password')->passwordInput() ?>
    <?php echo $form->field($model, 'password_repeat')->passwordInput() ?>
    <?php echo $form->field($model, 'firstname')->textInput() ?>
    <?php echo $form->field($model, 'lastname')->textInput() ?>
    <?php echo $form->field($model, 'status')->radioList(['停用','启用']) ?>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '保存' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
