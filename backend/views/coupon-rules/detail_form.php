<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;

/* @var $this yii\web\View */
/* @var $model api\models\V1\LotteryPrize */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lottery-prize-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

        <?php echo $form->field($model, 'img_url')->widget(
            FileAPI::className(),
            [
                'settings' => [
                    'url' => ['/prize-box/fileapi-upload']
                ]
            ]
        ) ?>

    <?= $form->field($model, 'coupon_id')->hiddenInput() ?>

    检索优惠券码或名称：<?= \yii\jui\AutoComplete::widget([
        'name' => 'search',
        'options' => ['id' => 'search', 'class' => 'form-control'],
        'clientOptions' => [
            'source' => "/customer/coupon-auto-complete"
        ],
        'clientEvents' => [
            'select' => 'function(event, ui) {
                                           var html="<li data-content="+ui.item.value+">"+ui.item.label+"<span class=\"del-item\" style=\"float: right;color: red;padding-right: 10px;\">删除</span></li>";
                                          $("#group_value").html(html);
                                          $("#couponrulesdetail-coupon_id").val(ui.item.value); 
                                          $("#search").val("");
                                          return false;
                                        }'
        ]
    ]) ?>
    优惠券：
    <ul id="group_value" style="height: 80px;border: 1px solid #eee;overflow-y: scroll;">
        <?php if($model->coupon_id){
            $coupon = $model->coupon;
            $coupon_model=[
                'OTHER'=>'其它',
                'ORDER'=>'订单券',
                'BUY_GIFTS'=>'买赠券',
                'BRAND'=>'品牌券',
                'CLASSIFY'=>'分类券',
                'PRODUCT'=>'商品券'
            ];
            if($coupon){
                $label ="[".$coupon->code."]---".$coupon->name."---".$coupon_model[$coupon->model?$coupon->model:"OTHER"];
                ?>
                <li data-content="<?php echo $coupon->coupon_id?>"><?php echo $label?><span class="del-item" style="float: right;color: red;padding-right: 10px;">删除</span></li>
            <?php
            }
            ?>
    <?php }?>


    </ul>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->beginBlock("JS_Block") ?>
$("body").on("click",'.del-item',function(){
    $("#group_value").html("");
    $("#couponrulesdetail-coupon_id").val("");
});
<?php $this->endBlock() ?>

<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'], \yii\web\View::POS_END);