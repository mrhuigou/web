<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;
/* @var $this yii\web\View */
/* @var $model api\models\V1\Lottery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lottery-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
<!--    --><?php //= $form->field($model, 'image')->widget(
//        FileAPI::className(),
//        [
//            'settings' => [
//                'url' => ['/prize-box/fileapi-upload']
//            ]
//        ]
//    ) ?>
    <?= $form->field($model, 'chances_per_customer')->textInput()->label("每个用户可抽奖次数") ?>
    <?= $form->field($model, 'start_time',['options'=>['class'=>'']])->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ]) ?>

    <?= $form->field($model, 'end_time',['options'=>['class'=>'']])->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
