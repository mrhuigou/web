<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */
/* @var $model api\models\V1\GroundPushPlan */

$this->title = '地推订单收货操作: ' ;

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

            <?php echo Html::input('text','order_id','',[ 'class' => 'form-control','id'=>'input_order_id'])?>


            <div class="form-group">
                <a href="javascript:void(0)"  class='btn btn-primary mbtn greenbtn w SubmitBtn'>确认收货 </a>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
    <script>
        <?php $this->beginBlock("JS_Block")?>

        $("body").on("click",".SubmitBtn",function () {
            var order_id = $("#input_order_id").val();
            if(!order_id){
                alert("订单id必填");
            }
            $.post('<?=\yii\helpers\Url::to('/ground-push-plan/receive',true)?>',{order_id:order_id,_csrf:"<?php echo Yii::$app->request->csrfToken;?>"},function(data){
                alert(data.message);
            },'json');
        });

        <?php $this->endBlock()?>
    </script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);


