<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;
/* @var $this yii\web\View */
/* @var $model api\models\V1\WeixinMessage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="weixin-message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'msgtype')->dropDownList(['text'=>'文本信息','news'=>'图文消息']) ?>
    <?= $form->field($model, 'key')->textInput(['maxlength' => 64]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => 8]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->widget(\common\extensions\widgets\kindeditor\KindEditor::className(),
        ['clientOptions' => ['allowFileManager' => 'true','allowUpload' => 'true']])  ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'picurl')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/try/fileapi-upload']
            ]
        ]
    ) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
