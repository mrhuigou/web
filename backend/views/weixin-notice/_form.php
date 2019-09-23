<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use devgroup\jsoneditor\Jsoneditor;
/* @var $this yii\web\View */
/* @var $model api\models\V1\WxNotice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wx-notice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'wx_notice_template_id')->dropDownList(\yii\helpers\ArrayHelper::merge(['0'=>'选择消息模板'],\yii\helpers\ArrayHelper::map(\api\models\V1\WxNoticeTemplate::find()->all(),'id','name'))) ?>

    <?= $form->field($model, 'content')->widget(Jsoneditor::className()) ?>

    <?= $form->field($model, 'link_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'push_date_type')->radioList(['0'=>'立即推送','1'=>'预约推送'])?>

    <?= $form->field($model, 'push_begin_time')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ]); ?>

    <?= $form->field($model, 'status')->radioList(['禁用','启用']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
