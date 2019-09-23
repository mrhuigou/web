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
    <?=$form->field($model, 'address')->textarea();?>
    <?=$form->field($model, 'order_status_id')->dropDownList(ArrayHelper::merge(['0'=>'全部','complate'=>"同步订单"],ArrayHelper::map(\api\models\V1\OrderStatus::find()->all(),'order_status_id', 'name')));?>
    <div class="btn-group">
		<?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <?php if(Yii::$app->request->getQueryParams()){?>
		<?= Html::a('导出', array_merge(['export'],Yii::$app->request->getQueryParams()), ['class' => 'btn btn-success']) ?>
        <?php }?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
