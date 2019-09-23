<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ClubTry */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if (\Yii::$app->getSession()->getFlash('error')): ?>
    <div class="alert alert-danger"><?=\Yii::$app->getSession()->getFlash('error')?></div class="alert alert-danger">
<?php endif ?>
<div class="club-try-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class'=>'form-horizontal'],
        'fieldConfig' => [
            'options'=>['class'=>'col-md-12']
        ],
    ]); ?>
    <?= $form->field($model, 'title',['options'=>['class'=>'col-md-12']])->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'image',['options'=>['class'=>'col-md-12']])->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/weixin-message/fileapi-upload']
            ]
        ]
    ) ?>
    <?= $form->field($model, 'product_code',['options'=>['class'=>'col-md-2']])->textInput()->label('商品编码') ?>
    <?= $form->field($model, 'quantity',['options'=>['class'=>'col-md-2']])->textInput() ?>
    <?= $form->field($model, 'price',['options'=>['class'=>'col-md-2']])->textInput(['maxlength' => 11]) ?>
    <?= $form->field($model, 'limit_user',['options'=>['class'=>'col-md-3']])->textInput() ?>
    <?= $form->field($model, 'limit_share_user',['options'=>['class'=>'col-md-3']])->textInput() ?>
    <div class="col-md-12" >
        <a class="btn btn-primary addcoupon">增加折扣券</a>
    </div>
    <div class="col-md-12" id="coupon">
    <?php if($model->tryCoupon) {
      foreach($model->tryCoupon as $key=> $coupon){
    ?>
    <div class="coupon_item">
        <?= $form->field($coupon, 'id',['template'=>'{input}'])->hiddenInput(['name'=>'coupon['.$key.'][id]']) ?>
    <?= $form->field($coupon, 'coupon_id',['options'=>['class'=>'col-md-10']])->dropDownList(ArrayHelper::map(\api\models\V1\Coupon::find()->where(['status'=>1])->asArray()->all(),'coupon_id','name'),['name'=>'coupon['.$key.'][coupon_id]']) ?>
        <div class="col-md-2 field-clubtrycoupon-action">
            <label class="control-label" for="clubtrycoupon-action">操作</label>
            <div class="del"><button type="button" title="清空" class="controlClear btn btn-primary" >删除</button></div>
            <div class="help-block"></div>
        </div>
    </div>
    <?php } }?>
    </div>
    <?= $form->field($model, 'begin_datetime',['options'=>['class'=>'col-md-6']])->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99 99:99:99',
      ]) ?>
    <?= $form->field($model, 'end_datetime',['options'=>['class'=>'col-md-6']])->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99 99:99:99',
      ]) ?>
    <?= $form->field($model, 'sort_order',['options'=>['class'=>'col-md-6']])->textInput() ?>
    <?= $form->field($model, 'status',['options'=>['class'=>'col-md-6']])->radioList(['0'=>'禁用','1'=>'启用']); ?>
    <?= $form->field($model, 'description')->widget(\common\extensions\widgets\kindeditor\KindEditor::className(),
        ['clientOptions' => ['allowFileManager' => 'true','allowUpload' => 'true']])  ?>
    <div class="form-actions top fluid ">
        <div class="col-md-12">
            <?= Html::submitButton($model->isNewRecord ? '创建提交' : '修改保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php $this->beginBlock('JS_END') ?>
var key=<?=isset($key)?$key+1:0?>;
$(".addcoupon").live("click",function(){
    var html = '<div class="coupon_item">';
    html += ' <div class="col-md-10 field-clubtrycoupon-coupon_id">';
    html += '<label class="control-label" for="clubtrycoupon-coupon_id">Coupon ID</label>';
   html += '<input  type="hidden" name="coupon['+key+'][id]" value="">';
    html += '<select  class="form-control" name="coupon['+key+'][coupon_id]">';
    <?php
    $data=ArrayHelper::map(\api\models\V1\Coupon::find()->where(['status'=>1])->asArray()->all(),'coupon_id','name');
    foreach($data as $key=>$value){
    ?>
    html += '<option value="<?=$key?>"><?=Html::encode($value)?></option>';
    <?php }  ?>
   html += '</select>';
    html += '<div class="help-block"></div>';
    html += '</div>';
    html += ' <div class="col-md-2 field-clubtrycoupon-action">';
    html += '<label class="control-label" for="clubtrycoupon-action">操作</label>';
    html += '<div class="del"><button type="button" title="清空" class="controlClear btn btn-primary" >删除</button></div>';
    html += '<div class="help-block"></div>';
    html += '</div>';
    html += '</div>';
    $("#coupon").append(html);
    key++;
    });
$(".del").live("click",function(){
    if(confirm("删除后不能恢复，确认删除？")){
    var id= $(this).parents(".coupon_item").find('input').val();
    if(id){
        $.post("<?php echo \yii\helpers\Url::to(['try/del-coupon'])?>", { id: id });
    }
    $(this).parents(".coupon_item").remove();
    }
    });
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>