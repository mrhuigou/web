<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;
/* @var $this yii\web\View */
/* @var $model api\models\V1\WeixinScansNews */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="weixin-scans-news-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'weixin_scans_id')->dropDownList(\yii\helpers\ArrayHelper::map(\api\models\V1\WeixinScans::find()->where('type>0')->orderBy('id desc')->all(),'id','title')) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pic_url')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/weixin-scans-news/fileapi-upload']
            ]
        ]
    ) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
