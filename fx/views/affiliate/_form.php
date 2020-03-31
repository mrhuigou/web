<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<section class="veiwport  pb50">

    <div class="flex-col tc whitebg bdb">
    </div>
    <?php $action = \yii\helpers\Url::to([Yii::$app->request->getUrl()])?>
	<?php $form = ActiveForm::begin(['id' => 'form-address', 'action'=>  $action,'fieldConfig' => [
		'template' => '<li><div class="t">{label}：</div><div class="c">{input}</div></li>{error}',
		'inputOptions' => ["class" => 'w f14'],
		'errorOptions' => ['class' => 'red pl5']
	],]); ?>

    <?= $form->field($model, 'province', ['template' => '{input}'])->hiddenInput(['id' => 'province'])->label(false) ?>
    <?= $form->field($model, 'city', ['template' => '{input}'])->hiddenInput(['id' => 'city'])->label(false) ?>
     <?= $form->field($model, 'district',['template' => '{input}'])->hiddenInput(['id' => 'district'])?>
    <ul class="line-book mt5">
        <div class="line-b whitebg p10 bd mt5 mb10">
            <?= $form->field($model, 'mode', ['labelOptions' => ['class' => 'fb f14 p10 ']])->inline()->radioList([ 'DOWN_LINE'=>'线下', 'ON_LINE'=>'线上'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline p10']],])?>
            <?= $form->field($model, 'type', ['labelOptions' => ['class' => 'fb f14 p10 ']])->inline()->radioList([ 'personal'=>'个人', 'company'=>'公司'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline p10']],])?>
            <?= $form->field($model, 'rebate_type', ['labelOptions' => ['class' => 'fb f14 p10 ']])->inline()->radioList([ 'order'=>'订单', 'product'=>'商品'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline p10']],])?>
            <?= $form->field($model, 'name', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '请填写名称'])->label('名称'); ?>
            <?= $form->field($model, 'username', ['inputOptions' => ["placeholder" => '请填写展示名称']])->label('展示名称'); ?>
            <?= $form->field($model, 'description', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '请填写描述'])->label('描述'); ?>
            <?= $form->field($model, 'contact_name', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '请填写联系人名称'])->label('联系人'); ?>
            <?= $form->field($model, 'telephone', ['inputOptions' => ["placeholder" => '请填写联系电话']])->label('联系电话'); ?>
            <li>
                <div class="t">选择地区：</div>
                <div class="c">
                    <div class="weui-cell__bd">
                        <?php $p = $model->province ? $model->province : '山东省';
                        $c = $model->city ? $model->city : '青岛市';
                        $d = $model->district ? $model->district : '市北区';
                        ?>
                        <input class="w f14" id="start" type="text"  value="<?php echo $p.' '.$c.' '.$d;?>">
                    </div>
                </div>

            </li>
            <?= $form->field($model, 'address',['template' => '{label}<li>{input}</li>{error}'])->textarea(["placeholder" => '小区/写字楼/街道+楼号+楼层等','id'=>'address','class'=>'w f14 ','rows'=>2,'style'=>"height:45px;padding:5px;"])?>


        </div>

	</ul>
	<div class=" bdt  p10 w tc ">
		<?= Html::submitButton('保存', ['class' => 'btn mbtn greenbtn w', 'name' => 'save-button']) ?>
	</div>
	<?php ActiveForm::end(); ?>

</section>
<script>
<?php $this->beginBlock("JS_QQDiTu") ?>
/*地址选择*/
$("#start").cityPicker({
    title: "选择地址",
    onChange: function (picker, values, displayValues) {
        $("#province").val(displayValues[0]);
        $("#city").val(displayValues[1]);
        $("#district").val(displayValues[2]);

    }
});
<?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['JS_QQDiTu'], \yii\web\View::POS_READY);
$this->registerJsFile("/assets/script/jqweui-picker.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/jqweui-city-picker.js",['depends'=>\h5\assets\AppAsset::className()]);

?>

