<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Page */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'type')->dropDownList(['pc'=>'pc','h5'=>'h5','all'=>'all']) ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'meta_keyword')->textInput() ?>
    <?= $form->field($model, 'meta_description')->textInput()?>
    <?= $form->field($model, 'image')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/page/fileapi-upload']
            ]
        ]
    ) ?>
    <?= $form->field($model, 'description')->widget(\common\extensions\widgets\kindeditor\KindEditor::className(),
	    ['clientOptions' => ['allowFileManager' => 'true','allowUpload' => 'true','filterMode'=>false]])  ?>
    <?= $form->field($model, 'sort_order')?>
    <?= $form->field($model, 'status',['options'=>['class'=>'col-md-12']])->radioList(['0'=>'禁用','1'=>'启用']); ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' =>  'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>