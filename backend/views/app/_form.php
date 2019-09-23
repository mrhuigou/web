<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;

/* @var $this yii\web\View */
/* @var $model api\models\V1\App */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'app_type')->textInput(['maxlength' => 255]) ?>

    <?php //echo $form->field($model, 'device_type')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'app_version')->textInput(['maxlength' => 255]) ?>

    <?php //echo $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'save_path')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/app/fileapi-upload']
            ]
        ]
    )->label("APK文件") ?>

    <?php //echo $form->field($model, 'download_url')->textInput(['maxlength' => 255]) ?>

    <?php $model->is_force = $model->is_force == 1?1:0;?>
    <?= $form->field($model, 'is_force',['options'=>['class'=>'']])->radioList(['0'=>'禁用','1'=>'启用']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '保存' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
