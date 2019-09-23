<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\extensions\widgets\fileapi\Widget as FileAPI;
/* @var $this yii\web\View */
/* @var $model api\models\V1\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'channel')->dropDownList(['PC版','H5']) ?>
	<?= $form->field($model, 'type')->dropDownList(['page'=>'专题','news'=>'文章']) ?>
    <?= $form->field($model, 'news_category_id')->dropDownList(ArrayHelper::map(\api\models\V1\NewsCategory::find()->all(),'id', 'name')) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'tag')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'image',['options'=>['class'=>'']])->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/weixin-message/fileapi-upload']
            ]
        ]
    )->label('图片') ?>
    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(\common\extensions\widgets\kindeditor\KindEditor::className(),
        ['clientOptions' => ['allowFileManager' => 'true','allowUpload' => 'true']])  ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'status')->radioList(['禁用','启用']) ?>

    <?= $form->field($model, 'highlight')->radioList(['普通显示','高亮显示']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
