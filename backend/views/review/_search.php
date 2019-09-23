<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use api\models\V1\ReturnStatus;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ReturnBaseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="return-base-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-horizontal'],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-4\">{error}</div>",  
            'labelOptions' => ['class' => 'col-md-1 control-label','style'=>'width:90px;'],  
        ], 
    ]); ?>

    <?php echo $form->field($model, 'review_id')->label("评论ID") ?>

    <?php echo $form->field($model, 'order_id') ?>
    <?php echo $form->field($model, 'author')->label("作者") ?>
    <?php echo $form->field($model, 'text')->label("评论内容") ?>



    <?php  echo  $form->field($model, 'date_added')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
      ])->label("评论时间");
    ?>

    <div class="form-actions top fluid ">
        <div class="col-md-offset-1 col-md-9" style="margin-left: 90px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
