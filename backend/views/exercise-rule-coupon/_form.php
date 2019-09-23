<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ExerciseRuleCoupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exercise-rule-coupon-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'exercise_rule_id')->hiddenInput(['value'=>Yii::$app->request->get('exercise_rule_id')])->label(false) ?>


	<?= $form->field($model, 'coupon_id')->widget(\yii\jui\AutoComplete::className(), [
		'options' => ['class' => 'form-control'],
		'clientOptions' => [
			'source' => "/exercise-rule-coupon/auto-complete",
		],
	]) ?>

    <?= $form->field($model, 'probability')->textInput() ?>
	<?= $form->field($model, 'share_status')->radioList(['通用','仅限助力者获得']) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
