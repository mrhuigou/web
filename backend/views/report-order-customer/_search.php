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
    <?=$form->field($model, 'group')->dropDownList(['data'=>'指定内容','all'=> '全部']);?>
    <div class="form-group field-group_value">
        <label class="col-md-1 control-label" style="width:90px;" for="reportordercustomersearch-end_date">检索内容</label>
        <div class="col-md-4">
        <?= \yii\jui\AutoComplete::widget( [
            'name' => 'search',
            'options' => ['id' => 'search','class'=>'form-control'],
            'clientOptions' => [
                'source' => "/report-order-customer/auto-complete"
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
	<?=$form->field($model, 'group_value')->textarea(['rows'=>10,'id'=>'group_value']);?>
	<?=$form->field($model, 'order_status_id')->dropDownList(ArrayHelper::merge(['0'=>'全部','complete'=>"有效订单"],ArrayHelper::map(\api\models\V1\OrderStatus::find()->all(),'order_status_id', 'name')));?>
    <div class="btn-group">
		<?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
		<?php if(Yii::$app->request->getQueryParams()){?>
			<?= Html::a('导出', array_merge(['export'],Yii::$app->request->getQueryParams()), ['class' => 'btn btn-success']) ?>
		<?php }?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
