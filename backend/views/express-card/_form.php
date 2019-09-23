<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ExpressCard */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
    $legal_persons = \common\models\LegalPerson::find()->select(['legal_person_id','name'])->where(['status'=>1])->all();
foreach($legal_persons as $value){
    $legal_person_sources[]=[
        'value'=>$value['legal_person_id'],
        'label'=>$value['name'],
    ];
}
?>

<div class="express-card-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'legal_person_name')->widget(\yii\jui\AutoComplete::className(), [
        'options' => ['class' => 'form-control','id'=>'legal_person_name'],
        'clientOptions' => [
            'source' => \yii\helpers\Url::to(['/express-card/get-legal-person']),
        ],
        'clientEvents'=>[
            'select' => 'function(event, ui) {
                  $("#legal_person_id").val(ui.item.value);
                  $("#legal_person_name").val(ui.item.label);
                  return false;
                }'
        ]
    ]) ?>
<?= $form->field($model,'legal_person_id')->hiddenInput(['id'=>'legal_person_id'])->label(false);?>
    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>
    <?php  echo  $form->field($model, 'begin_datetime')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("开始时间");
    ?>

    <?php  echo  $form->field($model, 'end_datetime')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("结束时间");
    ?>


    <?= $form->field($model, 'status')->dropDownList([0=>'停用',1=>'启用']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
