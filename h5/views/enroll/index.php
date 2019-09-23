<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/28
 * Time: 15:58
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '报名表';
?>
<header class="fx-top opc-f lh44 bluebg white">
	<div class="flex-col tc">
		<a class="flex-item-2" href="/">
			<i class="aui-icon aui-icon-home  f28"></i>
		</a>
		<div class="flex-item-8 f16">
			<?= Html::encode($this->title) ?>
		</div>
		<a class="flex-item-2 share-guide " href="javascript:;">
			<i class="aui-icon aui-icon-share  f28"></i>
		</a>
	</div>
</header>
<style>
	.required:before{content: "*";color:red;font-size: 12px;}
</style>
<section class="veiwport pl5 pr5 pt50  ">
	<?php $form = ActiveForm::begin(['id' => 'form-address','fieldConfig' => [
		'template' => "{label}<div class=\"mt10 mb10 clearfix\">{input}</div>{error}",
		'inputOptions' => ["class"=>'input linput tl w'],
		'errorOptions'=>['class'=>'red mt5 mb5 db']
	],  ]);?>
	<div class="m10">
	<?= $form->field($model, 'realname') ?>
	<?= $form->field($model, 'telephone') ?>
	<?= $form->field($model, 'quantity')->dropDownList(['1'=>'1人','2'=>'2人','3'=>'3人']) ?>
	<?= $form->field($model, 'company') ?>
	<?= $form->field($model, 'position') ?>
	<?= $form->field($model, 'address') ?>
	<?= $form->field($model, 'industry') ?>
	<?= $form->field($model, 'service')->textarea(['class'=>'textarea w']) ?>
	<?= $form->field($model, 'remark')->textarea(['class'=>'textarea w']) ?>
	</div>
	<div class="fx-bottom tc bdt whitebg p10">
		<?= Html::submitButton('确认报名', ['class' => 'btn lbtn bluebtn w', 'name' => 'save-button']) ?>
	</div>
	<?php ActiveForm::end(); ?>
</section>