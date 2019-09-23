<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ReturnBase */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="return-base-form">

    <?php $form = ActiveForm::begin(); ?>

    <table class="table table-striped table-bordered detail-view" style="width:85%;border:1px dotted rgb(204, 204, 204);">
        <tbody><tr>
            <td>回复评论</td>
            <td></td>
        </tr>
        <tr>
            <td><span class="required">*</span> 回复内容</td>
            <td>
                <?= $form->field($model, 'text')->textarea(['cols'=>60,'rows'=>8,'class'=>""])->label(false) ?>
            </td>
        </tr>

        <tr>
            <td>回复状态</td>
            <td>
                <?= $form->field($model, 'status')->dropDownList(['0'=>'停用','1'=>'启用'],['class'=>""])->label(false) ?>
              </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </td>
        </tr>

        </tbody></table>



    <?php ActiveForm::end(); ?>

</div>
