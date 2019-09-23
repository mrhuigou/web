<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ReturnBase */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="return-base-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'review_id')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'customer_id')->textInput(['readonly' => true,'style'=>'width:90px' ]) ?>
    <?= $form->field($model, 'author')->textInput(['readonly' => true]) ?>
    <?= Html::a("查看用户信息",\yii\helpers\Url::to(["/customer/view",'id'=>$model->customer_id]),['target'=>'_blank','style'=>''])?> <br/><br/><br/>

    <?= $form->field($model, 'text')->textInput(['readonly' => false])->label("评论内容") ?>

    <?= $form->field($model, 'rating')->textInput(['readonly' => false,'style'=>'width:150px' ])->label("商品") ?>
    <?= $form->field($model, 'service')->textInput(['readonly' => false,'style'=>'width:150px' ])->label("服务") ?>
    <?= $form->field($model, 'delivery')->textInput(['readonly' => false,'style'=>'width:150px' ])->label("快递") ?>

    <?= $form->field($model, 'status')->dropDownList(['0'=>'停用','1'=>'启用']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
