<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ExpressCardProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="express-card-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'express_card_id')->dropDownList( ArrayHelper::merge(['' => '请选择'], ArrayHelper::map(\api\models\V1\ExpressCard::find()->all(),'id', 'name')) )?>


    <?= $form->field($model, 'shop_code')->hiddenInput(['maxlength' => true,'id'=>'shop_code'])->label(false) ?>

    <?= $form->field($model, 'product_base_code')->hiddenInput(['maxlength' => true,'id'=>'product_base_code'])->label(false) ?>


    <?= $form->field($model, 'product_code')->widget(\yii\jui\AutoComplete::className(), [
        'options' => ['class' => 'form-control','id'=>'product_code'],
        'clientOptions' => [
            'source' => \yii\helpers\Url::to(['/express-card/get-products']),
        ],
        'clientEvents'=>[
            'select' => 'function(event, ui) {
                    $.post("/express-card/get-product", { product_code: ui.item.value },function(data){
                        if(data["status"]){
                            $("#shop_code").val(data["shop_code"]);
                            $("#product_base_code").val(data["product_base_code"]);
                            $("#product_name").val(data["product_name"]);
                            
                        }else{
                            alert(data["message"]);
                        }
                         
                    } );
                  
                  $("#product_code").val(ui.item.value);
                  return false;
                }'
        ]
    ])->label("输入商品code自动完成") ?>
    <?= $form->field($model, 'product_name')->textInput(['maxlength' => true,'id'=>'product_name'])->label('商品名称') ?>


    <?= $form->field($model, 'quantity')->textInput()->label("数量") ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->label("描述") ?>

    <?= $form->field($model, 'status')->dropDownList(['0'=>'停用','1'=>'启用']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
