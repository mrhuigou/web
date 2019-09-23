<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use api\models\V1\CmsType;
use common\extensions\widgets\fileapi\Widget as FileAPI;

/* @var $this yii\web\View */
/* @var $model api\models\V1\InformationDescription */
/* @var $form yii\widgets\ActiveForm */
$model->description = Html::decode($model->description);
?>

<div class="information-description-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'type')->dropDownList(ArrayHelper::map(CmsType::find()->all(),'cms_type_id', 'name')) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 64]) ?>
    <?= $form->field($model, 'meta_keyword')->textInput(['maxlength' => 64]) ?>
    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 64]) ?>
    <?= $form->field($model, 'image')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/information/fileapi-upload']
            ]
        ]
    ) ?>
    <?= $form->field($model, 'description')->widget(\common\extensions\widgets\kindeditor\KindEditor::className(),
        ['clientOptions' => ['allowFileManager' => 'true','allowUpload' => 'true']])  ?>
    <?= $form->field($model2, 'status',['options'=>['class'=>'col-md-12']])->radioList(['0'=>'禁用','1'=>'启用']); ?>
    <?= $form->field($model2, 'bottom',['options'=>['class'=>'col-md-12']])->radioList(['0'=>'禁用','1'=>'启用']); ?>
    <?= $form->field($model2, 'show_type',['options'=>['class'=>'col-md-12']])->radioList(['default'=>'默认','cms'=>'自定义页面']); ?>
    <?= $form->field($model2, 'sort_order')->textInput(['maxlength' => 64]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
