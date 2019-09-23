<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */
/* @var $model api\models\V1\GroundPushPlan */

$this->title = '地推订单退货操作: ' ;

?>

<div class="page-content">
    <!-- BEGIN STYLE CUSTOMIZER -->
    <?=\backend\widgets\Customizer::widget();?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                控制台 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

<div class="ground-push-plan-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput(['maxlength' => true])->label("订单id") ?>
    <?= $form->field($model, 'return_method')->radioList(['RETURN_PAY'=>'仅退款','RETURN_GOODS'=>'退货（仅当需要取货时）'],['style'=>"margin-left:20px; padding-left:20px;",'id'=>'radiovalue'])->label("退货方式") ?>
    <?= $form->field($model, 'product_code')->textInput(['maxlength' => true])->label("商品code") ?>

    <div class="form-group">
        <a href="javascript:void(0)"  class='btn btn-primary mbtn greenbtn w SubmitBtn'>确认退货 </a>
    </div>

    <?php ActiveForm::end(); ?>

</div>

    </div>
<script>
    <?php $this->beginBlock("JS_Block")?>

    $("body").on("click",".SubmitBtn",function () {
        var order_id = $("#returngroundpushfrom-order_id").val();
        if(!order_id){
            alert("订单id必填");
        }
        var product_code = $("#returngroundpushfrom-product_code").val();
        var return_method = $("input[name='ReturnGroundPushFrom[return_method]']:checked").val();

        $.post('<?=\yii\helpers\Url::to('/ground-push-plan/return-ground-push',true)?>',{order_id:order_id,product_code:product_code,return_method:return_method,_csrf:"<?php echo Yii::$app->request->csrfToken;?>"},function(data){

            alert(data.message);
        },'json');
    });

    <?php $this->endBlock()?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);


