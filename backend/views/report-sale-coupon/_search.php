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
    <div class="form-group field-group_value">
        <label class="col-md-1 control-label" style="width:90px;" for="reportordercustomersearch-end_date">检索内容</label>
        <div class="col-md-4">
			<?= \yii\jui\AutoComplete::widget( [
				'name' => 'search',
				'options' => ['id' => 'search','class'=>'form-control'],
				'clientOptions' => [
					'source' => "/report-sale-coupon/coupon-auto-complete"
				],
				'clientEvents'=>[
					'select' => 'function(event, ui) {
                if($("#group_value").val()){
                 var arr=$("#group_value").val().split("\r\n");
                }else{
                var arr=[];
                }
                   arr.push(ui.item.value);
                  $("#group_value").val(arr.join("\r\n")); 
                  $("#search").val("");
                  return false;
                }'
				]
			]) ?>
        </div>
    </div>
	<?=$form->field($model, 'coupons')->textarea(['rows'=>10,'id'=>'group_value']);?>
    <div class="form-actions top fluid ">
        <div class="col-md-offset-1 col-md-9" style="margin-left: 90px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
