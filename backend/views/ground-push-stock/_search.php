<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\searchs\GroundPushStockSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ground-push-stock-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ground_push_point_id')->dropDownList(\yii\helpers\ArrayHelper::merge(['0' => '全部'], \yii\helpers\ArrayHelper::map(\api\models\V1\GroundPushPoint::find()->orderBy("id desc")->limit(20)->all(), 'id', 'name')))->label("地推点名称") ?>

    <?= $form->field($model, 'product_code')->label("商品码") ?>


    <?php // echo $form->field($model, 'last_time') ?>

    <?php // echo $form->field($model, 'version') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
