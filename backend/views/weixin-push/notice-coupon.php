<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model api\models\V1\WeixinScans */

$this->title = '优惠券过期提醒';
$this->params['breadcrumbs'][] = ['label' => '微信推送', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
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
                <?= Html::encode($this->title) ?>
            </h3>
            <?= \yii\widgets\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>

    <?php $form = ActiveForm::begin(); ?>
    <h3>需要提醒的优惠券</h3>
    <style>
        .radio{min-height: 0};
    </style>
    <div class="form-group field-coupon-code">
        <?php echo Html::radioList('type',0,[0=>'全部优惠券',1=> '指定优惠券'],[

            'itemOptions' => ['labelOptions' => ['class' => 'radio-inline']],
            'onchange' => 'if( ($(this).find(":radio:checked").val() ==1) ){ 
				$(".tab_1").show();
				}else {
				   $(".tab_1").hide();
				} 
			'
        ])?>




    <div class="tab_1 " style="display: none;margin-top: 20px;">
        检索优惠券码或名称：<?= \yii\jui\AutoComplete::widget([
            'name' => 'search',
            'options' => ['id' => 'search', 'class' => 'form-control'],
            'clientOptions' => [
                'source' => "/customer/coupon-auto-complete"
            ],
            'clientEvents' => [
                'select' => 'function(event, ui) {
                                               var html="<li data-content="+ui.item.value+">"+ui.item.label+"<span class=\"del-item\" style=\"float: right;color: red;padding-right: 10px;\">删除</span></li>";
                                              $("#group_value").append(html); 
                                              $("#search").val("");
                                              return false;
                                            }'
            ]
        ]) ?>
        优惠券：
        <ul id="group_value" style="height: 80px;border: 1px solid #eee;overflow-y: scroll;"></ul>

    </div>
    </div>
    <div class="form-group ">
        <label class="control-label" for="coupon-code">口令</label>
        <?php echo Html::input('text','pass','',[ 'class' => 'form-control'])?>
        <div class="help-block"></div>
    </div>

    <div class="btn-group" style="margin-top: 10px;">
        <?= Html::button('立即推送', ['class' => 'btn btn-primary', 'id' => 'coupon_submit']) ?>
    </div>





    <?php ActiveForm::end(); ?>

    <script>
<?php $this->beginBlock("JS_Block") ?>
        $("#coupon_submit").on("click",function(){
            var type = $("input[name='type']:checked").val();
            var pass = $("input[name='pass']").val();

            var arr=[];
            $("#group_value li").each(function(){
                arr.push($(this).data("content"));
            });
            $.post('<?= \yii\helpers\Url::to(['/weixin-push/notice-coupon']) ?>',{coupons:arr,type:type,pass:pass},function(res){
                if(res.status){
                    alert(res.msg);
                    location.reload(true);
                }else{
                    alert(res.msg);
                }
        },'json');
        });
$("body").on("click",'.del-item',function(){
    $(this).parent("li").remove();
});
<?php $this->endBlock() ?>

<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'], \yii\web\View::POS_END);?>
    </script>
</div>
