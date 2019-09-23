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


    <?php  echo  $form->field($model, 'begin_date')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
      ]);
    ?>
        <?php  echo  $form->field($model, 'end_date')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
      ]);

    ?>
    <?=$form->field($model, 'group')->dropDownList(['year'=> '年度','month'=> '月度', 'week'=> '周','day'=> '天']);?>
    <?=$form->field($model, 'order_status_id')->dropDownList(ArrayHelper::merge(['0'=>'全部','complate'=>"有效订单"],ArrayHelper::map(\api\models\V1\OrderStatus::find()->all(),'order_status_id', 'name')));?>


    <div class="form-actions top fluid ">
        <div class="col-md-offset-1 col-md-9" style="margin-left: 90px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
