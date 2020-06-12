<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\WeixinScans */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="weixin-scans-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'type')->dropDownList([1=>'普通',2=>'地推推广','3'=>'扫街推广']) ?>
    <?= $form->field($model, 'code')->textInput()->label("标识(扫街人姓名代码，地推推广代码)") ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
