<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<section class="veiwport  pb50">

    <div class="flex-col tc whitebg bdb">
<!--	    <a class="flex-item-6 p5 cur bdr f14">手工输入</a>-->
<!--	    <a class="flex-item-6 p5 f14 gray9 " id="pop-map">-->
<!--		    <i class="iconfont vm f14">&#xe622;</i>地图定位-->
<!--	    </a>-->
    </div>
    <?php $action = \yii\helpers\Url::to([Yii::$app->request->getUrl()])?>
	<?php $form = ActiveForm::begin(['id' => 'form-address', 'action'=>  $action,'fieldConfig' => [
		'template' => '<li><div class="">{input}</div></li>{error}',
		'inputOptions' => ["class" => 'w f14'],
		'errorOptions' => ['class' => 'red pl5']
	],]); ?>
    <ul class="line-book mt5">
        <div class="line-b whitebg p10 bd mt5 mb10">
            <?= $form->field($model, 'type_invoice', ['labelOptions' => ['class' => 'fb f14 p10 ']])->inline()->radioList([ 1=>'个人', 2=>'增值税普票',3=>'增值税专票'], [
                'itemOptions' => ['labelOptions' => ['class' => 'radio-inline p10']],
                'onchange' => 'if( ($(this).find(":radio:checked").val() ==1)){ 
				$(".tab_1").show();$(".tab_2").show();$(".tab_3").hide();
				}else if($(this).find(":radio:checked").val() ==2){
				   $(".tab_1").show();$(".tab_2").show(); $(".tab_3").hide();
				}else if($(this).find(":radio:checked").val() ==3){
				     $(".tab_1").show();$(".tab_2").show(); $(".tab_3").show();
				} 
				else{
				   $(".tab_1").show();$(".tab_2").show(); $(".tab_3").show(); 
				} 
				
				 '
            ])?>
            <div class="invoice_value tab_1" style="">
                <p class="org f12 lh150">客户开具发票的必须提供税号（公司）、身份证号（个人）。</p>
                <div>
                    <?= $form->field($model, 'title_invoice', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 50, 'placeholder' => '个人姓名/单位名称'])->label(false); ?>
                    <div class="invoice_value tab_2"  >
                        <?= $form->field($model, 'code', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 20, 'placeholder' => '身份证号/公司税号'])->label(false); ?>
                        <div class="invoice_value tab_3 " style="display:<?php if( $model->type_invoice == 3){ echo "block";}else{ echo "none";}?>">
                            <?= $form->field($model, 'address_and_phone', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '注册地址及电话'])->label(false); ?>
                            <?= $form->field($model, 'bank_and_account', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '开户行及账号'])->label(false); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

	</ul>
	<div class=" bdt  p10 w tc ">
		<?= Html::submitButton('保存', ['class' => 'btn mbtn greenbtn w', 'name' => 'save-button']) ?>
	</div>
	<?php ActiveForm::end(); ?>
<!--	<div id="map-contain" style="position:fixed; left:0; top:0; width:100%; height:100%; border:none;z-index:999;display: none;" class="layermchild  layermanim">-->
<!--			<div style="bottom: 0px;" class="pa-t">-->
<!--				<iframe width="100%" height="100%" frameborder="0" src="https://apis.map.qq.com/tools/locpicker?search=1&type=1&key=GNWBZ-7FSAR-JL5WY-WIDVS-FHLY2-JVBEC&referer=365jiarun" id="mapPage"></iframe>-->
<!--			</div>-->
<!--	</div>-->

</section>
<script>
<?php $this->beginBlock("JS_QQDiTu") ?>

<?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['JS_QQDiTu'], \yii\web\View::POS_READY);

?>

