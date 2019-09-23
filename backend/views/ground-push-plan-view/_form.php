<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\GroundPushPlanView */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ground-push-plan-view-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'ground_push_plan_id')->textInput() ?>
    <?= $form->field($model, 'ground_push_plan_id')->dropDownList( \yii\helpers\ArrayHelper::map(\api\models\V1\GroundPushPlan::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'product_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'max_buy_qty')->textInput() ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?php echo $form->field($model, 'status')->dropDownList(['0'=>'停用','1'=>'启用']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
