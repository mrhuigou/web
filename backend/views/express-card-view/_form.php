<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ExpressCardView */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="express-card-view-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'express_card_id')->dropDownList(ArrayHelper::merge(['' => '请选择'], ArrayHelper::map(\api\models\V1\ExpressCard::find()->all(),'id', 'name')) )->label("所属提货卡") ?>

    <?= $form->field($model, 'card_no')->textInput(['maxlength' => true])->label("卡号") ?>

    <?= $form->field($model, 'card_pwd')->textInput(['maxlength' => true])->label("卡密") ?>

    <?= $form->field($model, 'status')->dropDownList([0=>'未激活',1=>'已激活'] ) ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
