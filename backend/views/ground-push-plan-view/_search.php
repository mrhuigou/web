<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\searchs\GroundPushPlanViewSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ground-push-plan-view-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'ground_push_plan_id')->dropDownList(\yii\helpers\ArrayHelper::merge(['0' => '全部'], \yii\helpers\ArrayHelper::map(\api\models\V1\GroundPushPlan::find()->limit(20)->all(), 'id', 'name'))) ?>

    <?= $form->field($model, 'product_code') ?>

    <?php echo $form->field($model, 'status')->dropDownList(['all'=>'全部' ,'0'=>'停用','1'=>'启用']) ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
