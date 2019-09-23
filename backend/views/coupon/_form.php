<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'model')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'type',['options'=>['class'=>'']])->radioList(['F'=>'固定金额','P'=>'百分比','D'=>'折扣']); ?>

    <?= $form->field($model, 'discount')->textInput(['maxlength' => 15]) ?>
    <?= $form->field($model, 'max_discount')->textInput() ?>
    <?= $form->field($model, 'min_discount')->textInput() ?>
    <?= $form->field($model, 'limit_min_quantity')->textInput() ?>
    <?= $form->field($model, 'limit_max_quantity')->textInput() ?>
    <?= $form->field($model, 'total')->textInput(['maxlength' => 15]) ?>
    <?= $form->field($model, 'limit_max_total')->textInput(['maxlength' => 15]) ?>
    <?= $form->field($model, 'date_type',['options'=>['class'=>'']])->radioList(['TIME_SLOT'=>'时间段','DAYS'=>'有效期秒数']); ?>
        <?= $form->field($model, 'date_start',['options'=>['class'=>'']])->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99 99:99:99 ',
      ]) ?>

        <?= $form->field($model, 'date_end',['options'=>['class'=>'']])->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '9999-99-99 99:99:99 ',
      ]) ?>
    <?= $form->field($model, 'expire_seconds')->textInput() ?>
    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'user_limit')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'image_url',['options'=>['class'=>'']])->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/weixin-message/fileapi-upload']
            ]
        ]
    ) ?>
    <?= $form->field($model, 'shipping',['options'=>['class'=>'']])->radioList(['0'=>'否','1'=>'是']); ?>

    <?= $form->field($model, 'is_entity',['options'=>['class'=>'']])->radioList(['0'=>'否','1'=>'是']); ?>

    <?= $form->field($model, 'is_open',['options'=>['class'=>'']])->radioList(['0'=>'否','1'=>'是']); ?>
    <?= $form->field($model, 'receive_begin_date',['options'=>['class'=>'']])->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99 ',
    ]) ?>

    <?= $form->field($model, 'receive_end_date',['options'=>['class'=>'']])->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99 ',
    ]) ?>
    <?= $form->field($model, 'status',['options'=>['class'=>'']])->radioList(['0'=>'禁用','1'=>'启用']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
