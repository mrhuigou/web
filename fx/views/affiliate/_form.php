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
		'template' => '<li><div class="">{input}</div></li>{error}',
		'inputOptions' => ["class" => 'w f14'],
		'errorOptions' => ['class' => 'red pl5']
	],]); ?>
    <ul class="line-book mt5">
        <div class="line-b whitebg p10 bd mt5 mb10">
            <?= $form->field($model, 'mode', ['labelOptions' => ['class' => 'fb f14 p10 ']])->inline()->radioList([ 'DOWN_LINE'=>'线下', 'ON_LINE'=>'线上'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline p10']],])?>
            <?= $form->field($model, 'type', ['labelOptions' => ['class' => 'fb f14 p10 ']])->inline()->radioList([ 'personal'=>'个人', 'company'=>'公司'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline p10']],])?>
            <?= $form->field($model, 'rebate_type', ['labelOptions' => ['class' => 'fb f14 p10 ']])->inline()->radioList([ 'order'=>'订单', 'product'=>'商品'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline p10']],])?>
            <?= $form->field($model, 'name', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '名称'])->label(); ?>
            <?= $form->field($model, 'username', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '展示名称'])->label(); ?>
            <?= $form->field($model, 'description', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '描述'])->label(); ?>
            <?= $form->field($model, 'contact_name', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '联系人名称'])->label(); ?>
            <?= $form->field($model, 'address', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '地址'])->label(); ?>
<!--            --><?//= $form->field($model, 'telephone', ['inputOptions' => ["placeholder" => '请填写收货人电话号码']]) ?>
        </div>

	</ul>
	<div class=" bdt  p10 w tc ">
		<?= Html::submitButton('保存', ['class' => 'btn mbtn greenbtn w', 'name' => 'save-button']) ?>
	</div>
	<?php ActiveForm::end(); ?>

</section>
<script>
<?php $this->beginBlock("JS_QQDiTu") ?>

<?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['JS_QQDiTu'], \yii\web\View::POS_READY);

?>

