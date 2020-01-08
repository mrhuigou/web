<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;
/* @var $this yii\web\View */
/* @var $model api\models\V1\ShareLogoScans */
/* @var $form yii\widgets\ActiveForm */

$logo_type = array(
    1 => '默认',
    2 => '促销方案',
    3 => '商品详情',
    4 => '页面专题',
);

?>

<div class="share-logo-scans-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'weixin_scans_id')->dropDownList(\yii\helpers\ArrayHelper::map(\api\models\V1\WeixinScans::find()->where('type>0')->orderBy('id desc')->all(),'id','title')) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList($logo_type) ?>

    <?= $form->field($model, 'parameter')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logo_url')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/share-logo-scans/fileapi-upload']
            ]
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
