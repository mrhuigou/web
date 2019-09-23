<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use api\models\V1\ReturnStatus;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ReturnBase */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="return-base-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'order_id')->widget(\yii\jui\AutoComplete::className(), [
        'options' => ['class' => 'form-control','id'=>'order_id'],
        'clientOptions' => [
            'source' => \yii\helpers\Url::to(['/order/get-order']),
        ],
        'clientEvents'=>[
            'select' => 'function(event, ui) {
                    $.post("/refund/return-auto-select", { order_id: ui.item.value },function(data){
                        if(data["status"]){
                            $("#order_no").val(data["order_no"]);
                            $("#date_ordered").val(data["date_ordered"]);
                            $("#customer_id").val(data["customer_id"]);
                            $("#firstname").val(data["firstname"]);
                            $("#telephone").val(data["telephone"]);
                            $("#orderProducts").html(data["orderProducts"]);
                        }else{
                            alert(data["message"]);
                        }
                         
                    } );
                  
                  $("#order_id").val(ui.item.value);
                  return false;
                }'
        ]
    ])->label("输入订单id自动完成") ?>

    <?= $form->field($model, 'order_no')->textInput(['readonly' => true,'id'=>'order_no']) ?>

    <?= $form->field($model, 'date_ordered')->textInput(['readonly' => true,'id'=>'date_ordered']) ?>

    <?= $form->field($model, 'customer_id')->textInput(['readonly' => true,'id'=>'customer_id']) ?>

    <?= $form->field($model, 'firstname')->textInput(['id'=>'firstname']) ?>

    <?= $form->field($model, 'telephone')->textInput(['id'=>'telephone']) ?>

    <?= $form->field($model, 'return_status_id')->dropDownList(ArrayHelper::map(ReturnStatus::find()->all(),'return_status_id', 'name')) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6])->label('退货原因') ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'is_all_return')->dropDownList(['部分退货','全退']) ?>
	<?= $form->field($model, 'return_method')->dropDownList(['RETURN_GOODS'=>'仅退货','RESHIP'=>'仅换货','RETURN_PAY'=>'仅退款']) ?>

    <div class=" form-group">
        <span>退货相关商品</span>
        <div class="col-xs-12">
<table id="orderProducts" class="table table-striped table-hover"></table>
</div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
