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

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'img_url')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/prize-box/fileapi-upload']
            ]
        ]
    ) ?>
    <?= $form->field($model, 'user_total_limit')->textInput()->label("每个用户可领取次数") ?>
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
