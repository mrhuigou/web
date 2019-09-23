<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ExpressCardViewSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="express-card-view-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'express_card_id')->dropDownList(ArrayHelper::merge(['0' => '请选择'], ArrayHelper::map(\api\models\V1\ExpressCard::find()->all(),'id', 'name')) )->label("所属提货卡") ?>

    <?= $form->field($model, 'card_no')->label('卡号'); ?>

    <?= $form->field($model, 'status')->dropDownList([''=>'全部',1=>'已激活',0=>'未激活'])->label("状态") ?>

    <?php // echo $form->field($model, 'version') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
