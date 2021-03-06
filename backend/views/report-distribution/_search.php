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


	<?php echo $form->field($model, 'begin_date')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
		'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]);
	?>
	<?php echo $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
		'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]);
	?>
    <div class="btn-group">
      <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
	  <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
